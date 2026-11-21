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
      if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
      }

      $portalLinkModel = new PortalLinkModel();
      return $this->response->setJSON([
        'data' => $portalLinkModel->select('link, title')->orderBy('id_portal', 'desc')->findAll()
      ]);
    }
  }
}
