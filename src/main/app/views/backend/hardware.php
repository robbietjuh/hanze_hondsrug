<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/backend">Backend</a></li>
            <li class="active">Configuratiemanagement</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-2">Soort</th>
                            <th class="col-md-5">Identificatiecode</th>
                            <th class="col-md-3">Merk</th>
                            <th class="col-md-2">Locatie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($this->data['hardwarelist'] as $ci) { ?>
                        <tr>
                            <td><?=ucfirst($ci['soort']);?></td>
                            <td>
                                <a href="/backend/hardware/<?=$ci['identificatiecode'];?>">
                                    <?=strtoupper($ci['identificatiecode']);?>
                                </a>
                            </td>
                            <td><?=ucfirst($ci['merk']);?></td>
                            <td><?=ucfirst($ci['locatie']);?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <hr />

                <div style="text-align: right">
                    <a href="/backend/hardware/create" class="btn btn-default">Nieuw configuratie-item</a>
                </div>
            </div>
        </div>

<?=$this->renderView("footer");?>