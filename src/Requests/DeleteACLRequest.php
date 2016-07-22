<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class DeleteACLRequest extends Request
{

    private $aclId;


    /**
     * DeleteACLRequest Constructor
     *
     */
    public function __construct($aclId = null)
    {
        $this->aclId = $aclId;
    }


    public function getAclId()
    {
        return $this->aclId;
    }


    public function setAclId($aclId)
    {
        $this->aclId = $aclId;
    }

}
