<?php

namespace R2Packages\Framework\WalletTransaction;

use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\WalletTransaction\WalletTransactionEntity;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Ports\AbstractRepositoryPort;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

/**
 * Repository class for handling wallet transactions.
 */
class WalletTransactionRepository extends AbstractRepositoryPort
{

    /** @var string SQL query to be executed */
    protected $sql = 'SELECT * FROM wallet_transactions WHERE 1=1';
    protected $table = 'wallet_transactions';


    /** @var WalletTransactionEntity Entity used for hydration */
    protected WalletTransactionEntity $walletTransactionEntity;

    protected BaseUserRepository $baseUserRepository;

    /**
     * WalletTransactionRepository constructor.
     *
     * @param WalletTransactionEntity $walletTransactionEntity
     * @param DbRepository $dbRepository
     * @param PaginationMetta $paginationMeta
     * @param Request $request
     * @param BaseUserRepository $baseUserRepository
     */
    public function __construct(
        WalletTransactionEntity $walletTransactionEntity,
        DbRepository $dbRepository,
        PaginationMetta $paginationMeta,
        Request $request,
        BaseUserRepository $baseUserRepository
    ) {
        
        $this->baseUserRepository = $baseUserRepository;
        $this->walletTransactionEntity = $walletTransactionEntity;

        parent::__construct($dbRepository, $paginationMeta, $request);

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
     * Hydrate a WalletTransactionEntity from database array and attach User entity.
     *
     * @param array|null $data
     * @return WalletTransactionEntity|null
     */
    protected function hydrate($data)
    {
        $user = $this->baseUserRepository->find($data['user_id']);
        $walletTransaction = $this->walletTransactionEntity->newInstance($user, $data);
        return $walletTransaction;
    }


}