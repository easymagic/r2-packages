<?php

namespace R2Packages\Framework\Repositories;

use R2Packages\Framework\Entities\WalletTransactionEntity;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;

/**
 * Repository class for handling wallet transactions.
 */
class WalletTransactionRepository
{
    /** @var array Filters and data to shape queries */
    protected $data = [];

    /** @var int Pagination size for fetch operations */
    protected $size = 11;

    /** @var string SQL query to be executed */
    protected $sql = '';

    /** @var array SQL query parameters */
    protected $params = [];

    /** @var WalletTransactionEntity Entity used for hydration */
    protected WalletTransactionEntity $walletTransactionEntity;

    protected DbRepository $dbRepository;

    protected AuthUserService $authUserService;

    /**
     * WalletTransactionRepository constructor.
     *
     * @param WalletTransactionEntity $walletTransactionEntity
     * @param UserRepository $userRepository
     * @param DbRepository $dbRepository
     * @param PaginationMetta $paginationMeta
     * @param Request $request
     * @param AuthUserService $authUserService
     */
    public function __construct(
        WalletTransactionEntity $walletTransactionEntity,
        DbRepository $dbRepository,
        PaginationMetta $paginationMeta,
        Request $request,
        AuthUserService $authUserService
    ) {
        $this->walletTransactionEntity = $walletTransactionEntity;
        $this->dbRepository           = $dbRepository;
        $this->data                   = $request->data;
        $this->size                   = $paginationMeta->limit;
        $this->sql                    = 'SELECT * FROM wallet_transactions WHERE 1=1';
        $this->authUserService = $authUserService;

        
        $authenticatedUserEntity = $this->authUserService->getAuthUser();
        if (!$authenticatedUserEntity->isEmpty()){
           if ($authenticatedUserEntity->isAdmin() || $authenticatedUserEntity->isStaff()){
                // no filter admin and staff should see all wallet transactions
           } else {
                $this->filterByUserId($authenticatedUserEntity->id);
           }
        }


        $this->applyCommonFilters();

        $this->orderByIdDesc();
    }

    /**
     * Apply common filtering rules to SQL and parameters using $this->data.
     *
     * Filters by user_id, reference, type, amount, source, status,
     * approval_status, and duration (created_at within last X minutes).
     */
    protected function applyCommonFilters()
    {
        // User ID filter
        if (isset($this->data['user_id']) && $this->data['user_id'] > 0) {
            $this->filterByUserId($this->data['user_id']);
        }

        // Reference filter
        if (!empty($this->data['reference'])) {
            $this->filterByReference($this->data['reference']);
        }

        // Type filter (credit, debit)
        if (
            !empty($this->data['type']) &&
            in_array($this->data['type'], ['credit', 'debit'], true)
        ) {
            $this->filterByType($this->data['type']);
        }

        // Amount filter
        if (isset($this->data['amount']) && $this->data['amount'] > 0) {
            $this->filterByAmount($this->data['amount']);
        }

        // Source filter (paystack, manual, refund, adjustment)
        if (
            !empty($this->data['source']) &&
            in_array($this->data['source'], ['paystack', 'manual', 'refund', 'adjustment'], true)
        ) {
            $this->filterBySource($this->data['source']);
        }

        // Status filter (pending, successful, failed)
        if (
            !empty($this->data['status']) &&
            in_array($this->data['status'], ['pending', 'successful', 'failed'], true)
        ) {
            $this->filterByStatus($this->data['status']);
        }

        // Approval status filter (pending, approved, rejected)
        if (
            !empty($this->data['approval_status']) &&
            in_array($this->data['approval_status'], ['pending', 'approved', 'rejected'], true)
        ) {
            $this->filterByApprovalStatus($this->data['approval_status']);
        }

        // Duration filter (created_at within last N minutes)
        if (isset($this->data['duration']) && $this->data['duration'] > 0) {
            $this->filterByDuration($this->data['duration']);
        }

        // Order by id descending
        // $this->orderByIdDesc();


    }

    function orderByIdDesc(){
        $this->sql .= ' ORDER BY id DESC';
        return $this;
    }

    /**
     * Filter by user ID.
     *
     * @param int $userId
     * @return void
     */
    function filterByUserId($userId){
        $this->sql     .= ' AND user_id = ?';
        $this->params[] = $userId;
        return $this;
    }

    /**
     * Filter by reference.
     *
     * @param string $reference
     * @return void
     */
    function filterByReference($reference){
        $this->sql     .= ' AND reference = ?';
        $this->params[] = $reference;
        return $this;
    }

    /**
     * Filter by type.
     *
     * @param string $type
     * @return void
     */
    function filterByType($type){
        $this->sql     .= ' AND type = ?';
        $this->params[] = $type;
        return $this;
    }

    /**
     * Filter by amount.
     *
     * @param float $amount
     * @return void
     */
    function filterByAmount($amount){
        $this->sql     .= ' AND amount = ?';
        $this->params[] = $amount;
        return $this;
    }

    /**
     * Filter by source.
     *
     * @param string $source
     * @return void
     */
    function filterBySource($source){
        $this->sql     .= ' AND source = ?';
        $this->params[] = $source;
        return $this;
    }

    /**
     * Filter by status.
     *
     * @param string $status
     * @return void
     */
    function filterByStatus($status){
        $this->sql     .= ' AND status = ?';
        $this->params[] = $status;
        return $this;
    }

    /**
     * Filter by approval status.
     *
     * @param string $approvalStatus
     * @return void
     */
    function filterByApprovalStatus($approvalStatus){
        $this->sql     .= ' AND approval_status = ?';
        $this->params[] = $approvalStatus;
        return $this;
    }

    /**
     * Filter by duration.
     *
     * @param int $duration
     * @return void
     */
    function filterByDuration($duration){
        $this->sql     .= ' AND created_at >= ?';
        $this->params[] = date('Y-m-d H:i:s', strtotime('-' . (int)$duration . ' minutes'));
        return $this;
    }

    /**
     * Fetch paginated wallet transactions with filters.
     *
     * @return WalletTransactionEntity[]
     */
    public function fetch()
    {
        $rows = $this->dbRepository->paginate($this->sql, $this->size, $this->params);
        return array_map([$this, 'hydrate'], $rows);
    }

    /**
     * Fetch all wallet transactions that match filters.
     *
     * @return WalletTransactionEntity[]
     */
    public function fetchAll()
    {
        $rows = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map([$this, 'hydrate'], $rows);
    }

    /*
    // Example for extending functionality:
    public function fetchPendingByUserIDAndDuration($user_id, $duration = 30)
    {
        $sql = 'SELECT * FROM wallet_transactions WHERE user_id = ? AND created_at >= ? AND status = "pending"';
        $params = [
            $user_id,
            date('Y-m-d H:i:s', strtotime('-' . $duration . ' minutes'))
        ];
        $rows = dbFetchAll($sql, $params);
        return array_map([$this, 'hydrate'], $rows);
    }
    */

    /**
     * Count wallet transactions matching the current query and filters.
     *
     * @return int
     */
    public function count()
    {
        return $this->dbRepository->count($this->sql, $this->params);
    }

    /**
     * Find a wallet transaction by its ID.
     *
     * @param int $id
     * @return WalletTransactionEntity|null
     */
    public function find($id)
    {
        $row = $this->dbRepository->fetchOne('SELECT * FROM wallet_transactions WHERE id = ?', [$id]);
        return $this->hydrate($row);
    }

    /**
     * Hydrate a WalletTransactionEntity from database array and attach User entity.
     *
     * @param array|null $data
     * @return WalletTransactionEntity|null
     */
    private function hydrate($data)
    {
        $walletTransaction = $this->walletTransactionEntity->newInstance($data);
        return $walletTransaction;
    }

    /**
     * Save a wallet transaction (insert or update).
     *
     * @param int $id Existing transaction ID or 0 for insert
     * @param array $data Transaction fields
     * @return WalletTransactionEntity|null
     */
    public function save($id, $data)
    {
        if ($id > 0) {
            $this->dbRepository->update('wallet_transactions', $data, ['id' => $id]);
        } else {
            $id = $this->dbRepository->insert('wallet_transactions', $data);
        }
        return $this->find($id);
    }

}