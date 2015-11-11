$(function() {
    var LanVorbereitungBackend = function() {
        this.url = 'module.php?module=lan_vorbereitung';
        this.hash = $('#view_lan_vorbereitung_container').data('user-hash');
    };

    LanVorbereitungBackend.prototype.getOverview = function() {
       $.ajax({
           url: this.url,
           data: {
               ajax: 'getFrontendData',
               hash: this.hash
           }
       }).done(function(data){
           $view.userName = data.name;
           $view.userNickname = data.nickname;
           $view.userOrt = data.userort;
           $view.userMail = data.email;
           $view.htmlLanVorbereitungView = ich.ich_lan_vorbereitung_accordion(data);
           $view.buildMainView();
       });
    };

    LanVorbereitungBackend.prototype.modifiyUserInformation = function() {
        $.ajax({
            url: this.url,
            type: 'POST',
            data: {
                ajax: 'modifyUserInformation',
                hash: this.hash,
                name: $('#user_name').val(),
                nickname: $('#user_nickname').val(),
                ort: $('#user_ort').val(),
                email: $('#user_email').val()
            }
        }).done(function(data){
			if (data !== null) {
				$view.showDialogInfo('Fehler', 'Fehler beim Speichern der Daten. Bitte Dennis/Mercix kontaktieren.');
			} else {
				$view.showDialogInfo('Daten gespeichert', 'Daten erfolgreich gespeichert.');
			}
		});
    };

    LanVorbereitungBackend.prototype.getLanGames = function(id) {
        $.ajax({
            url: this.url,
            data: {
                ajax: 'getGames',
                hash: this.hash,
                lan_id: id
            }
        }).done(function(data){
           $view.htmlLanGames = ich.ich_lan_games(data);
           $view.buildView(id);
        });
    };

    LanVorbereitungBackend.prototype.getLanTermine = function(id) {
        $.ajax({
            url: this.url,
            data: {
                ajax: 'getTermineByLanId',
                id: id,
                hash: this.hash
            }
        }).done(function(data){
			$view.htmlLanTermine = ich.ich_lan_termine(data);
			$.each(data.termine, function (index, value){
				$view.termine.push(value.termin);
			});
			$view.buildView(id);
        });
    };

    LanVorbereitungBackend.prototype.sendNewInvite = function() {
        $.ajax({
           url: this.url,
           type: 'POST',
           data: {
               ajax: 'addNewUser',
               name: $('#dialog_input_name').val(),
               email: $('#dialog_input_email').val(),
               hash: this.hash
           }
        }).done(function(data) {
            if (data.error != undefined) {
                $view.showDialogInfo('Fehler', data.error);
            } else {
                $view.showDialogInfo('Erfolg', 'Einladung erfolgreich verschickt');
            }
        });
    };

    LanVorbereitungBackend.prototype.addNewGame = function(lan_id) {
        console.log(lan_id);
        $.ajax({
            url: this.url,
            type: 'POST',
            data: {
                ajax: 'addNewGame',
                name: $('#dialog_game_name').val(),
                art: $('#dialog_game_art').val(),
                min_user: $('#dialog_game_min_user').val(),
                max_user: $('#dialog_game_max_user').val(),
				description: $('#dialog_game_description').val(),
				link: $('#dialog_game_link').val(),
                hash: this.hash
            }
        }).done(function() {
            $ajax.getLanGames(lan_id);
        });
    };

    LanVorbereitungBackend.prototype.addNewTermin = function(lan_id) {
        $.ajax({
            url: this.url,
            type: 'POST',
            data: {
                ajax: 'addTerminByLanId',
                lan_id: lan_id,
                termin: $('#dialog_input_termin').val(),
                hash: this.hash
            }
        }).done(function() {
            $ajax.getLanTermine(lan_id);
        });
    };

    LanVorbereitungBackend.prototype.showZusagen = function(termin_id) {
        $.ajax({
            url: this.url,
            data: {
                ajax: 'getTerminZusagen',
                termin_id: termin_id,
                hash: this.hash
            }
        }).done(function(data) {
            $view.htmlAcceptedUser = ich.ich_lan_user(data);
            $view.showDialogAcceptedUser();
        });
    };

	LanVorbereitungBackend.prototype.showGameDetails = function( game_id ) {
		$.ajax({
			url: this.url,
			data: {
				ajax: 'getGameDetails',
				hash: this.hash,
				game_id: game_id
			}
		}).done(function(data) {
			$view.htmlLanGameDetails = ich.ich_lan_games_details_dialog(data);
			$view.showDialogGameDetails();
		});
	};

    LanVorbereitungBackend.prototype.changeTerminStatus = function(lan_id, termin_id, status) {
        $.ajax({
            url: this.url,
            type: 'POST',
            data: {
                ajax: 'changeTerminStatus',
                termin_id: termin_id,
                status: status,
                hash: this.hash
            }
        }).done(function() {
            $ajax.getLanTermine(lan_id);
        });
    };

    LanVorbereitungBackend.prototype.changeLanGameStatus = function(lan_id, game_id, status) {
        $.ajax({
            url: this.url,
            type: 'POST',
            data: {
                ajax: 'changeGamesVoting',
                lan_id: lan_id,
                game_id: game_id,
                status: status,
				hash: this.hash
            }
        }).done(function(){
            $ajax.getLanGames(lan_id);
        });
    };

    var LanVorbereitungFrontend = function() {
        // icanhaz html vars
        this.htmlLanVorbereitungView;
        this.htmlLanTermine;
        this.htmlLanGames;
		this.htmlLanGameDetails;
        this.htmlAcceptedUser;
        // user values
        this.userName = '';
        this.userNickname = '';
        this.userOrt = '';
        this.userMail = '';
		// termine
		this.termine = [];
    };

    LanVorbereitungFrontend.prototype.buildView = function(id) {
        // termin tab
        $('#lan_vorbereitung_termine_'+id).html($view.htmlLanTermine);
        // games tab
        $('#lan_vorbereitung_games_'+id).html($view.htmlLanGames);
        $view.addTerminTabHandler();
        $view.addGameTabHandler();
    };

    LanVorbereitungFrontend.prototype.buildMainView = function() {
        $('#view_lan_vorbereitung_container').append($view.htmlLanVorbereitungView);
        $('#view_lan_vorbereitung_container').accordion({
            heightStyle: 'content'
        });
        // set input values with userData
        $('#user_name').val($view.userName);
        $('#user_nickname').val($view.userNickname);
        $('#user_ort').val($view.userOrt);
        $('#user_email').val($view.userMail);
        // set input vals to stored user data
        $view.addHandler();
    };

    LanVorbereitungFrontend.prototype.addHandler = function() {
        // Tab Eigene Daten
        $('body').on('click', '#add_new_user', function() {
            $view.showDialogNewInvite();
        });
        $('body').on('click', '#user_data_save', function() {
           $ajax.modifiyUserInformation();
        });
        // Acoordion Lan Click
        $('body').on('click', '.accordion_lan_key', function() {
            var lan_id = $(this).data('lan-id');
            $ajax.getLanTermine(lan_id);
        });
        // tab handler
        $('body').on('click', '.lan_tab_termine', function() {
            var lan_id = $(this).parents('div').prop('id');
            $ajax.getLanTermine(lan_id);
        });
        $('body').on('click', '.lan_tab_games', function() {
            var lan_id = $(this).parents('div').prop('id');
            $ajax.getLanGames(lan_id);
        });
        $('.lan_details_tab').tabs();
        // Termin Tab Handler
        $('body').on('click', '.add_new_termin', function() {
            $view.showDialogAddTermin( this );
        });
        $('body').on('click', '.add_new_game', function() {
            $view.showDialogAddGame( this );
        });
    };

    LanVorbereitungFrontend.prototype.addGameTabHandler = function() {
        $('.rank_up,.rank_down,.get_game_details').unbind('click');
        $('.rank_up').button({
            icons: { primary: 'ui-icon-arrowthick-1-n' }
        }).click(function() {
            var lan_id      = $(this).parents('div').data('lan-id');
            var game_id     = $(this).parents('tr').data('game-id');
            $ajax.changeLanGameStatus(lan_id, game_id, 'rate_up');
        });
        $('.rank_down').button({
            icons: { primary: 'ui-icon-arrowthick-1-s' }
        }).click(function() {
            var lan_id      = $(this).parents('div').data('lan-id');
            var game_id     = $(this).parents('tr').data('game-id');
            $ajax.changeLanGameStatus(lan_id, game_id, 'rate_down');
        });
        $('.get_game_details').button({
            icons: { primary: 'ui-icon-search' }
        }).click(function() {
			var game_id      = $(this).parents('tr').data('game-id');
            $ajax.showGameDetails(game_id);
        });
    };

    LanVorbereitungFrontend.prototype.addTerminTabHandler = function() {
        $('.show_zusagen,.lan_absage,.lan_zusage').unbind('click');
        $('.show_zusagen').button({
            icons: { primary: 'ui-icon-search' }
        }).click(function() {
            var termin_id   = $(this).parents('tr').data('termin-id');
            $ajax.showZusagen(termin_id);
        });
        $('.lan_zusage').button({
            icons: { primary: 'ui-icon-check' }
        }).click(function() {
            var termin_id   = $(this).parents('tr').data('termin-id');
            var lan_id      = $(this).parents('div').data('lan-id');
            $ajax.changeTerminStatus(lan_id, termin_id, true);
        });
        $('.lan_absage').button({
            icons: { primary: 'ui-icon-close' }
        }).click(function() {
            var termin_id   = $(this).parents('tr').data('termin-id');
            var lan_id      = $(this).parents('div').data('lan-id');
            $ajax.changeTerminStatus(lan_id, termin_id, false);
        });
		// Takes a 1-digit number and inserts a zero before it
		function padNumber(number) {
			var ret = new String(number);
			if (ret.length === 1)
				ret = "0" + ret;
			return ret;
		}
		$('#lan_termine_calendar').datepicker({
			firstDay: 1,
			dateFormat: 'dd.mm.yy',
			beforeShowDay: function(date) {
				var year = date.getFullYear();
				// months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
				var month = padNumber(date.getMonth() + 1);
				var day = padNumber(date.getDate());
				// This depends on the datepicker's date format
				var dateString = day + '.' + month + '.' + year;

				var gotDate = $.inArray(dateString, $view.termine);

				if (gotDate >= 0) {
					// Enable date so it can be deselected. Set style to be highlighted
					return [false, "ui-state-highlight"];
				}
				// Dates not in the array are left enabled, but with no extra style
				return [false, ""];
			}
		});
		$('#lan_termine_calendar').datepicker('setDate', $view.termine[0]);
    };

    // Frontend Dialoge

	LanVorbereitungFrontend.prototype.showDialogGameDetails = function() {
		$('#lan_show_information').html($view.htmlLanGameDetails);
        $('#lan_show_information').dialog({
            title: 'Spieldetails',
			width: 600,
            buttons: [{
                text: 'Ok',
                click: function() {
                    $(this).dialog('close');
                }
            }]
        });
	};

    LanVorbereitungFrontend.prototype.showDialogInfo = function(title, message) {
        var dialog = '<div>' + message + '</div>';
        $(dialog).dialog({
			title: title,
			buttons: [{
				text: 'OK',
				click: function() {
					$(this).dialog('close');
				}
			}]
        });
    };

    LanVorbereitungFrontend.prototype.showDialogAcceptedUser = function() {
        $('#lan_show_information').html($view.htmlAcceptedUser);
        $('#lan_show_information').dialog({
            title: 'Angemeldete Benutzer',
			width: 300,
            buttons: [{
                text: 'Ok',
                click: function() {
                    $(this).dialog('close');
                }
            }]
        });
    };

    LanVorbereitungFrontend.prototype.showDialogAddTermin = function( button ) {
        var lan_id = $(button).parents('div').data('lan-id');
        // datepicker init with german setting
        $('#dialog_input_termin').not('.hasDatepicker').datepicker({
			dateFormat: 'dd.mm.yy',
			firstDay: 1
		});

        $('#lan_vorbereitung_dialog_add_termin').dialog({
           title: 'Neuen Termin erstellen' ,
           buttons: [{
               text: 'Speichern',
               click: function() {
                   $ajax.addNewTermin(lan_id);
				   $(this).dialog('close');
               }
           },{
               text: 'Abbrechen',
               click: function() {
                   $(this).dialog('close');
               }
           }],
           open: function(){
               $(this).children('input').val('');
           }
        });
    };

    LanVorbereitungFrontend.prototype.showDialogAddGame = function( button ) {
        var lan_id = $(button).parents('div').data('lan-id');

        $('#lan_vorbereitung_dialog_add_game').dialog({
            title: 'Neues Spiel erfassen' ,
            width: 450,
            modal: true,
            buttons: [{
                text: 'Speichern',
                click: function() {
                    $ajax.addNewGame(lan_id);
                    $(this).dialog('close');
                }
            },{
                text: 'Abbrechen',
                click: function() {
                   $(this).dialog('close');
                }
            }],
            open: function() {
                $(this).children('input').val('');
            }
        });
    };

    LanVorbereitungFrontend.prototype.showDialogNewInvite = function() {        
        $('#lan_vorbereitung_dialog_new_invite').dialog({
            title: 'Neue Einladung senden',
            width: 440,
            height: 210,
            modal: true,
            buttons: [{
                text: 'Senden',
                click: function() {
                    $(this).dialog('destroy');
                    $ajax.sendNewInvite();
                }
            },{
                text: 'Abbrechen',
                click: function() {
                    $(this).dialog('destroy');
                }
            }],
            open: function() {
                $(this).children('input').val('');
            }
        });
    };

    var $ajax = new LanVorbereitungBackend();
    var $view = new LanVorbereitungFrontend();

    $ajax.getOverview();
});
