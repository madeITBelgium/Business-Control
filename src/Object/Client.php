<?php

namespace MadeITBelgium\BusinessControl\Object;


/**
 * Business Control PHP SDK
 *
 * @version    0.0.1
 *
 * @copyright  Copyright (c) 2018 Made I.T. (http://www.madeit.be)
 * @author     Made I.T. <info@madeit.be>
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-3.txt    LGPL
 */
class Client
{
    private $bc = null;
    
    public function setBusinessControl($bc)
    {
        $this->bc = $bc;
    }
    
    public function all($page = 1, $params = []) {
        $params['page'] = $page;
        $query = http_build_query($this->bc->deleteEmptyValues($params));
        return $this->bc->get('/api/1.0/client/?' . $query);
    }
    
    public function searchByVat($vat, $companyId = null, $teamId = null, $page = 1) {
        $params = [
            'vat' => $vat,
            'company_id' => $companyId,
            'team_id' => $teamId,
        ];
        
        return $this->all($page, $params);
    }
    
    public function searchByEmail($email, $companyId = null, $teamId = null, $page = 1) {
        $params = [
            'email' => $email,
            'company_id' => $companyId,
            'team_id' => $teamId,
        ];
        
        return $this->all($page, $params);
    }
    
    public function searchByName($name, $companyId = null, $teamId = null, $page = 1) {
        $params = [
            'name' => $name,
            'company_id' => $companyId,
            'team_id' => $teamId,
        ];
        
        return $this->all($page, $params);
    }
    
    public function create($data = ['name' => null,  'company_id' => null, 'team_id' => null, 'client_group_id' => null, 'contact_name' => null, 'phone' => null, 'contact_phone' => null, 'email' => null, 'invoice_email' => null, 'contact_email' => null, 'street_name' => null, 'street_number' => null, 'postal_code' => null, 'postal_name' => null, 'country' => null, 'vat' => null]) {
        return $this->bc->post('/api/1.0/client', $data);
    }
    
    public function detail($id) {
        return $this->bc->get('/api/1.0/client/' . $id);
    }
    
    public function update($id, $data = ['name' => null,  'company_id' => null, 'team_id' => null, 'client_group_id' => null, 'contact_name' => null, 'phone' => null, 'contact_phone' => null, 'email' => null, 'invoice_email' => null, 'contact_email' => null, 'street_name' => null, 'street_number' => null, 'postal_code' => null, 'postal_name' => null, 'country' => null, 'vat' => null]) {
        return $this->bc->post('/api/1.0/client/' . $id, $data);
    }
    
    public function delete($id) {
        return $this->bc->delete('/api/1.0/client/' . $id, $data);
    }
    
}