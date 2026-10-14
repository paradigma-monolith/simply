<?php

namespace App\Controllers\PortalLink;

use App\Controllers\BaseController;
use App\Models\PortalLinkModel;

class Data extends BaseController
{
  public function setLink()
  {
    $data['title'] = "Portal Link | Pengelolaan Link";
    $data['activeMenu'] = "portalLinkSet";

    $portalLinkModel = new PortalLinkModel();
    $data['getData'] = $portalLinkModel->orderBy('id_portal', 'desc')->findAll();

    return view('portal_link/set_link', $data);
  }

  public function menu()
  {
    if ($this->request->isAJAX()) {
      $portalLinkModel = new PortalLinkModel();
      $msg['data'] = $portalLinkModel->orderBy('id_portal', 'desc')->findAll();

      echo json_encode($msg);
    }
  }
}
