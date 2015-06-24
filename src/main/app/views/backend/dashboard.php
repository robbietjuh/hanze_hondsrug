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
                <?php if(count($this->data['incidents']) > 0) { ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-2">Datum</th>
                            <th class="col-md-6">Omschrijving</th>
                            <th class="col-md-2">Werkstation</th>
                            <th class="col-md-2">Melder</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($this->data['incidents'] as $incident) { ?>
                        <tr>
                            <td><?=date_format(date_create($incident['datum']), "d-m-Y");?></td>
                            <td>
                                <a href="/backend/incident/<?=$incident['identificatiecode'];?>">
                                    <?=$incident['titel'];?>
                                </a>
                            </td>
                            <td><?=$incident['hardware_component'];?></td>
                            <td><?=$incident['melder']['weergavenaam'];?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <span class="okay-green">
                    <i class="glyphicon glyphicon-ok-circle great-icon"></i>
                    <h3 class="text-center">Alle incidenten zijn opgelost. Good job!</h3>
                </span>
                <?php } ?>
            </div>
        </div>

<?=$this->renderView("footer");?>