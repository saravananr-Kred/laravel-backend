<?php
use App\Models\audit_log;

if (!function_exists('ActivityLogger')) {
    function ActivityLogger($action, $module, $user_id = null, $ip_address)
    {
        audit_log::create([
            'action' => $action,
            'module' => $module,
            'user_id' => auth()->id ?? $user_id,
            'ip_address' => $ip_address ?? request()->ip(),
        ]);
    }
}

?>