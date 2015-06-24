<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/backend">Dashboard</a></li>
            <li><a href="/backend/questionnaires">Vragenlijsten</a></li>
            <li class="active">Nieuwe vragenlijst aanmaken</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8">
                <?php if(isset($this->data['error'])) { ?>

                    <div class="alert alert-danger">
                        <strong>De vragenlijst kon niet aangemaakt worden.</strong>
                        <p><?=$this->data['error'];?></p>
                    </div>

                <?php } ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="title">Titel voor vragenlijst</label>
                        <input type="text" name="title" id="title" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="icon">Naam van icoon</label>
                        <input type="text" name="icon" id="icon" class="form-control" required />
                    </div>

                    <div class="hidden question-template">
                        <div class="form-group">
                            <label class="question_title">Vraag #</label>
                            <input type="text" name="question" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label>Bij ja</label>

                            <div>
                                <input type="radio" name="yes_action" value="next_question" />
                                Opvolgende vraag weergeven
                            </div>

                            <div>
                                <input type="radio" name="yes_action" value="workaround" />
                                <input type="text" name="yes_workaround" class="form-control"
                                       style="width: 97%; display: inline-block;"
                                       placeholder="Workaround weergeven" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Bij nee</label>

                            <div>
                                <input type="radio" name="no_action" value="next_question" />
                                Opvolgende vraag weergeven
                            </div>

                            <div>
                                <input type="radio" name="no_action" value="workaround" />
                                <input type="text" name="no_workaround" class="form-control"
                                       style="width: 97%; display: inline-block;" placeholder="Workaround weergeven" />
                            </div>
                        </div>
                    </div>

                    <div class="questions"></div>

                    <hr />

                    <div class="text-right">
                        <button class="btn btn-default" onclick="return addQuestion(true);">Extra vraag toevoegen</button>
                        <input type="submit" class="btn btn-primary" value="Vragenlijst opslaan" />
                    </div>

                    <script>
                        var number_of_questions = 0;

                        /**
                         * Adds a new question to the DOM
                         */
                        function addQuestion(animate) {
                            if(animate === undefined) animate = false;
                            number_of_questions++;

                            // Generate a new question well
                            var newQuestion = $("<div/>").html($(".question-template").html()).addClass("well").hide();
                            $(".questions").append(newQuestion);

                            // Prepend question_n_ to every input name
                            $("input", newQuestion).each(function() {
                                $(this).attr("name", "question_" + number_of_questions + "_" + $(this).attr("name"))
                            });

                            // Update the question title
                            $(".question_title", newQuestion).text("Vraag #" + number_of_questions);

                            // Show the well
                            newQuestion.slideToggle(animate ? 300 : 0);

                            // Don't submit the form
                            return false;
                        }

                        $(document).ready(function() {
                            addQuestion();
                        });
                    </script>
                </form>
            </div>
        </div>

<?=$this->renderView("footer");?>