<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/backend">Backend</a></li>
            <li class="active">Vragenlijsten</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8">
                <?php if(count($this->data['questionnaires']) > 0) { ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-2">Datum</th>
                            <th class="col-md-6">Titel</th>
                            <th class="col-md-2">Icoon</th>
                            <th class="col-md-2">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($this->data['questionnaires'] as $qs) { ?>
                        <tr>
                            <td><?=date_format(date_create($qs['datum']), "d-m-Y");?></td>
                            <td>
                                <a href="/questionnaire/<?=$qs['id'];?>">
                                    <?=$qs['titel'];?>
                                </a>
                            </td>
                            <td><i class="glyphicon glyphicon-<?=$qs['icoon'];?>"></i> <?=$qs['icoon'];?></td>
                            <td>
                                <a href="/backend/questionnaires/<?=$qs['id'];?>/delete" class="text-danger">
                                    <i class="glyphicon glyphicon-trash"></i> Verwijderen
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <hr />
                <?php } ?>

                <a href="/backend/questionnaires/create" class="btn btn-default pull-right">Nieuwe vragenlijst</a>
            </div>
        </div>

<?=$this->renderView("footer");?>