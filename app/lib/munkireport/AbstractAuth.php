<?php

namespace munkireport\lib;

// Declare the interface 'AuthInterface'
abstract class AbstractAuth
{
    abstract public function login($login, $password);
    abstract public function getAuthMechanism();
    abstract public function getAuthStatus();
    abstract public function getUser();
    abstract public function getGroups();
    
    private function authorizeUserAndGroups($auth_config, $auth_data)
    {
        $checkUser = isset($auth_config['mr_allowed_users']);
        $checkGroups = isset($auth_config['mr_allowed_groups']);
        
        if( ! $checkUser && ! $checkGroups){
            return true;
        }
        
        if ($checkUser) {
            $admin_users = $this->valueToArray($auth_config['mr_allowed_users']);
            if (in_array(strtolower($auth_data['user']), array_map('strtolower', $admin_users))) {
                return true;
            }
        }
        // Check user against group list
        if ($checkGroups) {
        // Set mr_allowed_groups to array
            $admin_groups = $this->valueToArray($auth_config['mr_allowed_groups']);
            foreach ($auth_data['groups'] as $group) {
                if (in_array($group, $admin_groups)) {
                    return true;
                }
            }
        }//end group list check
        
        return false;
    }
    
    /**
     * Convert value to array or keep Array
     *
     * @param mixed $value string or array
     * @return return array
     */
    private function valueToArray($value='')
    {
        return is_array($value) ? $value : [$value];
    }

}