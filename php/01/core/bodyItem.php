      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $arr[$i]->denuncia; ?></h4>
      </div>
      <div class="modal-body">
        <img class="border" alt="" src="<?php echo $img2; ?>">

        <div class="btn-group">
            <button type="button" class="btn btn-default"><i class="icon-heart"></i>Me Gusta</button>
            <button type="button" class="btn btn-default"><i class="icon-map-marker"></i>Reubicar</button>
            <button type="button" class="btn btn-default"><i class="icon-share"></i>Compartir</button>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
