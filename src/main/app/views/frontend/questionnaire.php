<?=$this->renderView("frontend/header");?>

        <ol class="breadcrumb">
            <li><a href="/">Dashboard</a></li>
            <li><a href="/">Bekende incidenten</a></li>
            <li class="active"><?=htmlentities($this->data['questionnaire']['titel']);?></li>
        </ol>

        <div class="row">
            <div class="col-md-8">
                <h4><?=htmlentities($this->data['questionnaire']['titel']);?></h4>
                hier komt tekst
            </div>
            <div class="col-md-4">
                <h4>Meldt een nieuw incident</h4>
                <?=$this->renderView("frontend/frm_incident");?>
            </div>
        </div>

<?=$this->renderView("frontend/footer");?>