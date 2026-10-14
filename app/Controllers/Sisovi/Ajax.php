<?php

namespace App\Controllers\Sisovi;

use App\Controllers\BaseController;

class Ajax extends BaseController
{
    public function setPengalihan() {
        $id = '124212';
        $get = $this->sisoviPengalihanModel->where('id', $id)->first();
        $data = [
            'id' => $id,
        ];
        if($get['pengalihan'] == 'false') {
            $data += [
                'pengalihan' => 'true',
            ];
        } else {
            $data += [
                'pengalihan' => 'false',
            ];
        }
        if($this->sisoviPengalihanModel->save($data)) {
            if ($this->request->isAJAX()) {
                echo json_encode(['data' => 'true']);
            }
        }
    }
}
