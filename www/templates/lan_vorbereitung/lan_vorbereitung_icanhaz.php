<script type="text/html" id="ich_lan_vorbereitung_accordion">
    {{#lans}}
        <h3 class="accordion_lan_key" data-lan-id="{{lan_id}}">{{name}}</h3>
        <div class="lan_details_tab" id="{{lan_id}}">
            <ul>
                <li><a class="lan_tab_termine" href="#lan_vorbereitung_termine_{{lan_id}}">Termine</a></li>
                <li><a class="lan_tab_games" href="#lan_vorbereitung_games_{{lan_id}}">Spiele</a></li>
            </ul>
            <div id="lan_vorbereitung_termine_{{lan_id}}" data-lan-id="{{lan_id}}"></div>
            <div id="lan_vorbereitung_games_{{lan_id}}" data-lan-id="{{lan_id}}"></div>
        </div>
    {{/lans}}
</script>

<script type="text/html" id="ich_lan_termine">
    <table class="lan_vorbereitung_termine">
        <thead>
            <th>Termin</th>
            <th>Status</th>
            <th>Zusagen</th>
            <th>Aktionen</th>
        </thead>
        <tbody>
        {{#termine}}
        <tr data-termin-id="{{termin_id}}">
            <td>{{termin}}</td>
            <td class="lan_status_td"><span class="lan_status ui-icon 
				{{#status}}ui-icon-check{{/status}}
				{{^status}}ui-icon-close{{/status}}"></span>
            </td>
            <td>{{termin_count}}</td>        
            <td>
                <button class="lan_zusage" type="button" title="Termin zusagen" {{#status}}disabled{{/status}}></button>
                <button class="lan_absage" type="button" title="Termin abw&auml;hlen" {{^status}}disabled{{/status}}></button>
                <button class="show_zusagen" type="button" title="Alle Zusagen anzeigen"></button>
            </span>
        </tr>
        {{/termine}}
        </tbody>
    </table>
	<div id="lan_termine_calendar"></div>
    {{#level}}<button class="add_new_termin" type="button">Neuen Termin hinzuf&uuml;gen</button>{{/level}}
</script>

<script type="text/html" id="ich_lan_games">
    <button class="add_new_game" type="button">Neues Spiel hinzuf&uuml;gen</button>
    <table class="lan_vorbereitung_games">
        <thead>
            <th>Name</th>
            <th>Art/Genre</th>
            <th>Min. Spieler</th>
            <th>Max. Spieler</th>
            <th>Rating</th>
            <th>Aktionen</th>
        </thead>
        <tbody>
        {{#games}}
            <tr data-game-id="{{game_id}}">
                <td>{{name}}</td>
                <td>{{art}}</td>
                <td>{{user_min}}</td>
                <td>{{user_max}}</td>
                <td>{{rating}}</td>
                <td>
                    <button type="button" class="rank_up" title="Das Spiel will ich spielen"
							{{#auswahl}}
							{{^notvoted}}
							{{^status}}disabled{{/status}}
							{{/notvoted}}
							{{/auswahl}}></button>
                    <button type="button" class="rank_down" title="Das Spiel will ich nicht spielen"
							{{#auswahl}}
							{{^notvoted}}
							{{#status}}disabled{{/status}}
							{{/notvoted}}
							{{/auswahl}}></button>
                    <button type="button" class="get_game_details" title="Details zu dem Spiel ansehen"></button>
                </td>
            </tr>
        {{/games}}
        </tbody>
    </table>
</script>

<script type="text/html" id="ich_lan_user">
    <ul>
    {{#user}}
        <li>{{name}} aka "{{nickname}}"</li>
    {{/user}}
    </ul>
</script>

<script type="text/html" id="ich_lan_games_details_dialog">
	<label>Name:</label>
	<b>{{name}}</b>
	<br />
	<label>Genre</label>
	<b>{{art}}</b>
	<br />
	<label>Mindest Anzahl Spieler</label>
	<b>{{user_min}}</b>
	<br />
	<label>Maximal Anzahl Spieler</label>
	<b>{{user_max}}</b>
	<br />
	<label>Pers&ouml;nliches Rating</label>
	<b>
	{{#rating}}
		{{#notvoted}}Keins{{/notvoted}}
		{{^notvoted}}
			{{^status}}+1{{/status}}
			{{#status}}-1{{/status}}
		{{/notvoted}}
	{{/rating}}
	</b>
	<br />
	<label>Spielbeschreibung:</label>
	<p>{{description}}</p>
	<br />
	<br />
	<label>Link f&uuml;r mehr Details:</label>
	<br />
	{{#link}}<a href="{{link}}" target="_blank">{{link}}</a>{{/link}}{{^link}}- keine Angabe -{{/link}}
</script>