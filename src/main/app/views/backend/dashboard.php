<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li class="active">Backend</li>
            <li>Dashboard</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8">
                <h4>Overzicht van aangemelde incidenten</h4>
                <?=$this->renderView("backend/list_incidents");?>
            </div>
        </div>

<?=$this->renderView("footer");?>