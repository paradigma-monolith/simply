<div class="container d-flex justify-content-center">
  <ul class="list-group list-group-right-bar mt-5">
    <?php foreach ($dataSideRightUser as $data) : ?>
      <li class="list-group-item d-flex justify-content-between align-content-center">
        <div class="d-flex flex-row" onclick="viewProfile(<?= $data['id_user']; ?>)">
          <?php if ($data['image'] != "" && is_file(FCPATH . 'dist/img/avatar/' . $data['image'])) : ?>
          <img src="/dist/img/avatar/<?= esc($data['image'], 'attr'); ?>" width="40" />
          <?php else : ?>
          <img src="/dist/img/boxed-bg.jpg" width="40" />
          <?php endif; ?>
          <div class="ml-2" style="width: 100px;
height: 40px;
overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
">
            <h6 class="mb-0" data-toggle="tooltip" data-placement="top" title="<?= name_capitalize($data['nama']); ?>"><?= name_capitalize($data['nama']); ?></h6>
            <div class="about">
              <span>
                <?= $data['email']; ?>
              </span>
            </div>
          </div>
        </div>
        <div class="check" style="opacity: .5;">
          <div class="item-scale">
            <?php if ($data['email']) : ?>
              <i class="fas fa-envelope text-danger" onclick="openBoxEmail(this)" data-email="<?= $data['email']; ?>" data-toggle="tooltip" data-placement="top" title="Kirim Email"></i>
            <?php else : ?>
              <i class="fas fa-envelope text-secondary" data-toggle="tooltip" data-placement="top" title="Tidak ada Email"></i>
            <?php endif; ?>
          </div>
          <div class="item-scale">
            <!-- <i class="far fa-comment-dots text-success"></i> -->
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>