<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li>Backend</li>
            <li class="active">Dashboard</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8">
                <?=$this->renderView("backend/list_incidents");?>
            </div>
        </div>

<?=$this->renderView("footer");?>