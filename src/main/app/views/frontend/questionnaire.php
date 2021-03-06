<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/">Dashboard</a></li>
            <li><a href="/">Bekende incidenten</a></li>
            <li class="active"><?=htmlentities($this->data['questionnaire']['titel']);?></li>
        </ol>

        <div class="row">
            <div class="col-md-8">
                <h4 style="margin-bottom: 20px;"
                    class="questionnaire-title"><?=htmlentities($this->data['questionnaire']['titel']);?></h4>

                <span class="hidden questionnaire-id"><?=$this->data['questionnaire']['id'];?></span>

                <?php foreach($this->data['questionnaire_entries'] as $num => $entry) { ?>

                <div class="qa qa-entry-<?=$num+1;?> hidden" data-entry-id="<?=$num+1;?>">
                    <strong class="qa-question"><?=$num+1;?>. <?=$entry['vraag'];?></strong>

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
                        <p class="workaround-txt"><?=$entry["{$workaround_type}_workaround"];?></p>
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

                function addStepsToDescription() {
                    // Placeholders
                    var steps = '';
                    var next = true;

                    // Fetch steps
                    $(".qa").each(function() {
                        if (!$(this).hasClass("hidden")) {
                            // Fetch choices
                            var choice = $("input[type='radio']:checked", this);
                            if (choice.length > 0) {
                                steps += '\n' + $(".qa-question", this).text() + '\n';
                                steps += ' * Gekozen voor ' + choice.val() + '\n';
                            }
                        }
                    });

                    // Check for a workaround
                    $(".workaround").each(function() {
                        if(!$(this).hasClass("hidden")) {
                            steps += '\nGevonden workaround:\n';
                            steps += $("p.workaround-txt", this).text()
                        }
                    });

                    // Fill in the textarea
                    $("textarea[name='description']").val(
                        $("textarea[name='description']").val() +
                        '\n___________\n' +
                        'Dit incident is verstuurd na het invullen van vragenlijst ' +
                        '#' + $(".questionnaire-id").text() + ' ' + $(".questionnaire-title").text() + '.\n\n' +

                        'De volgende stappen zijn doorlopen: \n' +
                        steps
                    );
                }

                $(document).ready(function() {
                    // Placeholder which determines whether to continue looping or not
                    var next = true;

                    $(".qa").each(function() {
                        // Configure the radio buttons
                        $(".entry-radio", this).each(function() {
                            // Bind on change
                            $(this).change(function() {
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
                        if($(this).data('entry-id') == 1) $(this).removeClass("hidden");
                    });
                });
            </script>

            <div class="col-md-4">
                <h4>Meldt een nieuw incident</h4>
                <?=$this->renderView("frontend/frm_incident");?>
            </div>
        </div>

<?=$this->renderView("footer");?>