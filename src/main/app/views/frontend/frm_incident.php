                <form action="" method="post">

                    <?php if(isset($this->data['incident_success'])) { ?>

                    <div class="form-group">
                        <div class="alert alert-success"
                             role="alert" style="margin-top: 20px;">
                            <strong>Uw incident is aangemeld.</strong><br /><br />
                            Onze medewerkers zullen zo snel mogelijk een vragenlijst aanmaken voor het oplossen
                            van het door u aangemelde incident.
                        </div>
                    </div>

                    <?php } else if(isset($this->data['incident_error'])) { ?>

                    <div class="form-group">
                        <div class="alert alert-danger"
                             role="alert" style="margin-top: 20px;">
                            <strong>Uw incident kon niet worden aangemeld.</strong><br /><br />
                            <?=$this->data['incident_error'];?>
                        </div>
                    </div>

                    <?php } ?>

                    <div class="form-group">
                        <label for="component">Hardware component</label>
                        <select id="component" name="component" class="form-control">
                            <?php foreach($this->data['componentlist'] as $component) { ?>
                            <option value="<?=$component['identificatiecode'];?>"><?=$component['identificatiecode'];?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="short_description">Korte omschrijving</label>
                        <input type="text" name="short_description" id="short_description" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="short_description">Volledige omschrijving</label>
                        <textarea class="form-control" style="height: 150px;" name="description"
                            placeholder="Beschrijft u hier alstublieft zo uitgebreid mogelijk uw vraag of probleem."></textarea>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary pull-right" name="create_incident" value="Incident melden &rarr;" />
                    </div>
                </form>