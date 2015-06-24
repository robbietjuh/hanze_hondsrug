<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/">Dashboard</a></li>
            <li><a href="/">Bekende incidenten</a></li>
            <li class="active"><?=htmlentities($this->data['questionnaire']['titel']);?></li>
        </ol>

        <div class="row">
            <div class="col-md-8">
                <h4 style="margin-bottom: 20px;"><?=htmlentities($this->data['questionnaire']['titel']);?></h4>

                <pre><?php print_r($this->data['questionnaire_entries']); ?></pre>

                <?php foreach($this->data['questionnaire_entries'] as $num => $entry) { ?>

                <div class="qa qa-entry-<?=$num+1;?>">
                    <strong><?=$num+1;?>. <?=$entry['vraag'];?></strong>

                    <div class="radio">
                        <label>
                            <input type="radio" class="entry-radio" name="options-qse-<?=$num+1;?>"
                                   data-questionnaire-entry-id="<?=$num+1;?>" value="yes" />
                            Ja
                        </label>
                    </div>

                    <div class="radio">
                        <label>
                            <input type="radio" class="entry-radio" name="options-qse-<?=$num+1;?>"
                                   data-questionnaire-entry-id="<?=$num+1;?>" value="no" />
                            Nee
                        </label>
                    </div>

                    <hr />

                    <?php foreach(array('ja', 'nee') as $workaround_type) { ?>
                    <?php if(!is_null($entry["{$workaround_type}_workaround"])) { ?>

                    <div class="alert alert-success">
                        <p><strong>Workaround voor dit probleem</strong></p>
                        <p><?=$entry["{$workaround_type}_workaround"];?></p>
                        <p>
                            Lost dit uw probleem niet op?
                            U kunt een nieuw incident aanmaken aan de rechterzijde van de pagina.
                        </p>
                    </div>

                    <?php } //if ?>
                    <?php } //foreach ?>
                </div>

                <?php } //foreach ?>
            </div>
            <div class="col-md-4">
                <h4>Meldt een nieuw incident</h4>
                <?=$this->renderView("frontend/frm_incident");?>
            </div>
        </div>

<?=$this->renderView("footer");?>