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