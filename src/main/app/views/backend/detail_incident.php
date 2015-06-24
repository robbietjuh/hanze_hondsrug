<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/backend">Backend</a></li>
            <li><a href="/backend">Incidenten</a></li>
            <li class="active"><?=htmlentities($this->data['incident']['titel']);?></li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8 incident-detail">
                <div class="well">
                    <div class="row">
                        <div class="col-md-4"><strong>Incident ID</strong></div>
                        <div class="col-md-8"><?=$this->data['incident']['identificatiecode'];?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Datum aangemeld</strong></div>
                        <div class="col-md-8"><?=date_format(date_create($incident['datum']), "d-m-Y");?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Omschrijving</strong></div>
                        <div class="col-md-8"><?=htmlentities($this->data['incident']['titel']);?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Werkstation</strong></div>
                        <div class="col-md-8"><?=$this->data['incident']['hardware_component'];?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Melder</strong></div>
                        <div class="col-md-8">
                            <?=$this->data['incident']['melder']['weergavenaam'];?>
                            (<?=$this->data['incident']['melder']['gebruikersnaam'];?>)
                        </div>
                    </div>
                </div>

                <?=nl2br($this->data['incident']['omschrijving']);?>

                <hr />

                <div style="text-align: right">
                    <a href="/backend/incident/<?=$this->data['incident']['identificatiecode'];?>/delete"
                       class="btn btn-danger">Incident verwijderen</a>
                    <a href="/backend/questionnaires/create" class="btn btn-default">Nieuwe vragenlijst</a>
                </div>
            </div>
        </div>

<?=$this->renderView("footer");?>