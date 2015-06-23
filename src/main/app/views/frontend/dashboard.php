<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li class="active">Dashboard</li>
        </ol>

        <div class="row">
            <div class="col-md-8">
                <h4>Waar heeft u hulp bij nodig?</h4>
                <?=$this->renderView("frontend/list_qas");?>
            </div>
            <div class="col-md-4">
                <h4>Meldt een nieuw incident</h4>
                <?=$this->renderView("frontend/frm_incident");?>
            </div>
        </div>

<?=$this->renderView("footer");?>