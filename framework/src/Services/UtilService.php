<?php

namespace R2Packages\Framework\Services;

class UtilService
{

    /**
     * Generate OTP
     * @return int
     */
    public function generateOtp()
    {
        return rand(100000, 999999);
    }

    /**
     * Refresh token
     * @param int $id
     * @return string
     */
    function refreshToken($id)
    {
        $token = $id . '_' . bin2hex(random_bytes(32));
        return $token;
    }
}
