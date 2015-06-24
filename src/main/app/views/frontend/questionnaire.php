<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/">Dashboard</a></li>
            <li><a href="/">Bekende incidenten</a></li>
            <li class="active"><?=htmlentities($this->data['questionnaire']['titel']);?></li>
        </ol>

        <div class="row">
            <div class="col-md-8">
                <h4 style="margin-bottom: 20px;"><?=htmlentities($this->data['questionnaire']['titel']);?></h4>

                <?php foreach($this->data['questionnaire_entries'] as $num => $entry) { ?>

                <div class="qa qa-entry-<?=$num+1;?> hidden" data-entry-id="<?=$num+1;?>">
                    <strong><?=$num+1;?>. <?=$entry['vraag'];?></strong>

                    <div class="radio">
                        <label>
                            <input type="radio" class="entry-radio" name="options-qse-<?=$num+1;?>"
                                   data-questionnaire-entry-id="<?=$num+1;?>" value="ja" />
                            Ja
                        </label>
                    </div>

                    <div class="radio">
                        <label>
                            <input type="radio" class="entry-radio" name="options-qse-<?=$num+1;?>"
                                   data-questionnaire-entry-id="<?=$num+1;?>" value="nee" />
                            Nee
                        </label>
                    </div>

                    <hr />

                    <?php foreach(array('ja', 'nee') as $workaround_type) { ?>
                    <?php if(!is_null($entry["{$workaround_type}_workaround"])) { ?>

                    <div class="alert alert-success hidden workaround workaround-<?=$workaround_type;?>">
                        <p><strong>Workaround voor dit probleem</strong></p>
                        <p><?=$entry["{$workaround_type}_workaround"];?></p>
                        <p>
                            <strong>Lost dit uw probleem niet op?</strong>
                            U kunt een nieuw incident aanmaken aan de rechterzijde van de pagina.

                            Om u beter van dienst te kunnen zijn, kunt u de stappen die u gevolgd heeft
                            in deze vragenlijst toevoegen aan uw omschrijving door
                            <a href="javascript: ;" onclick="addStepsToDescription();">hier</a> te klikken.
                        </p>
                    </div>

                    <?php } //if ?>
                    <?php } //foreach ?>
                </div>

                <?php } //foreach ?>
            </div>

            <script>
                function showQuestion(question_id) {
                    // For every Q&A entry...
                    $(".qa").each(function() {
                        // Check whether this specific Q&A entry is within the threshold
                        if($(this).data('entry-id') > question_id)
                            $(this).addClass("hidden"); // Nope - hide it
                        else if($(this).hasClass("hidden"))
                            $(this).removeClass("hidden"); // Yes - show it
                    });
                }

                $(document).ready(function() {
                    // Placeholder which determines whether to continue looping or not
                    var next = true;

                    for(var id = 1; next; id++) {
                        // Check whether the Q&A entry was found
                        next = ($(".qa-entry-" + id).length > 0);
                        if(!next) break; // Stop if no entries were found

                        console.log("Verwerken van " + id);

                        // Get the current Q&A entry
                        var current_entry = $(".qa-entry-" + id);

                        // Configure the radio buttons
                        $(".entry-radio", current_entry).each(function() {
                            // Bind on change
                            $(this).change(function() {
                                console.log("Change");

                                // Check whether the radio button is checked
                                if($(this).is(':checked')) {
                                    // Hide all workarounds
                                    var entry_id = $(this).data('questionnaire-entry-id');
                                    $(".workaround", $(".qa-entry-" + entry_id)).addClass("hidden");

                                    // Get workarounds related to the user's choice and show them. If nothing
                                    // was found, assume we need to proceed to the next question.
                                    var workaround = $(".workaround-" + $(this).val(), $(".qa-entry-" + entry_id));
                                    if(workaround.length > 0) {
                                        // Hide following questions and just show the workaround
                                        showQuestion(entry_id);
                                        workaround.removeClass("hidden");
                                    }
                                    else {
                                        // Show the next question
                                        showQuestion(entry_id + 1);
                                    }
                                }
                            });
                        });

                        // Show if this is the first entry
                        if(id == 1) $(".qa-entry-" + id).removeClass("hidden");
                    }
                });
            </script>

            <div class="col-md-4">
                <h4>Meldt een nieuw incident</h4>
                <?=$this->renderView("frontend/frm_incident");?>
            </div>
        </div>

<?=$this->renderView("footer");?>