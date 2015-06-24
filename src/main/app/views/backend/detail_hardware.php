<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/backend">Backend</a></li>
            <li><a href="/backend/hardware">Configuratiemanagement</a></li>
            <li class="active"><?=htmlentities($this->data['ci']['identificatiecode']);?></li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8 hardware-detail">
                <div class="well">
                    <div class="row">
                        <div class="col-md-4"><strong>Identificatiecode</strong></div>
                        <div class="col-md-8"><?=strtoupper($this->data['ci']['identificatiecode']);?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Soort</strong></div>
                        <div class="col-md-8"><?=ucfirst($this->data['ci']['soort']);?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Merk</strong></div>
                        <div class="col-md-8"><?=ucfirst($this->data['ci']['merk']);?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Leverancier</strong></div>
                        <div class="col-md-8"><?=ucfirst($this->data['ci']['leverancier']);?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Aanschaf jaar</strong></div>
                        <div class="col-md-8"><?=$this->data['ci']['aanschaf_jaar'];?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"><strong>Locatie</strong></div>
                        <div class="col-md-8"><?=ucfirst($this->data['ci']['locatie']);?></div>
                    </div>

                    <?php if(!empty($this->data['ci']['opmerkingen'])) { ?>

                    <div class="row">
                        <div class="col-md-4"><strong>Opmerkingen</strong></div>
                        <div class="col-md-8"><?=$this->data['ci']['opmerkingen'];?></div>
                    </div>

                    <?php } ?>
                </div>

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#software">Geinstalleerde software</a></li>
                    <li><a data-toggle="tab" href="#incidents">Gekoppelde incidenten</a></li>
                </ul>

                <div class="tab-content">
                    <div id="software" class="tab-pane active">
                        <?php if(count($this->data['software']) > 0) { ?>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-3">Producent</th>
                                    <th class="col-md-3">Naam</th>
                                    <th class="col-md-6">Soort</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($this->data['software'] as $entry) { ?>
                                <tr>
                                    <td><?=ucfirst($entry['producent']);?></td>
                                    <td><?=ucfirst($entry['naam']);?></td>
                                    <td><?=ucfirst($entry['soort']);?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                        <?php } else { ?>

                        <div class="alert alert-info">
                            Er is geen software aan deze hardware gekoppeld.
                        </div>

                        <?php } ?>
                    </div>
                    <div id="incidents" class="tab-pane">
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
                        <div class="alert alert-success">
                            <strong>Geen incidenten gevonden.</strong>
                            <p>Er zijn geen incidenten aan dit configuratie item gekoppeld.</p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

<?=$this->renderView("footer");?>