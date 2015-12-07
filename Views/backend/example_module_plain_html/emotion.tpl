{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    <div class="page-header">
        <h1>Emotions - AJAX loaded</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Assigned categories</th>
                <th>Devices</th>
                <th>Modified</th>
                <th>Active</th>
                <th>Is Landingpage?</th>
            </tr>
            </thead>
            <tbody class="ajax-content">
            </tbody>
        </table>
    </div>

    <script id="entry-template" type="text/x-handlebars-template">
    {literal}
        {{#each emotions}}
        <tr>
            <td><strong>{{name}}</strong></td>
            <td>
                {{#if categoriesNames}}
                    {{categoriesNames}}
                {{else}}
                    <em>Landingpage</em>
                {{/if}}
            </td>
            <td>{{deviceNames device}}</td>
            <td>{{modified}}</td>
            <td><input type="checkbox" readonly="readonly"{{#if active}} checked="checked"{{/if}}</td>
            <td><input type="checkbox" readonly="readonly"{{#if isLandingPage}} checked="checked"{{/if}}</td>
        </tr>
        {{/each}}
    {/literal}
    </script>
{/block}

{block name="content/javascript"}
    <script type="text/javascript" src="{link file="backend/_resources/js/handlebars-v3.0.3.js"}"></script>
    <script type="text/javascript">
        var url = "{url controller="ExampleModulePlainHtml" action="getEmotion"}",
            $content = $('.ajax-content');
        {literal}

        Handlebars.registerHelper('deviceNames', function(deviceIds) {
            var devices = [
                'XS',
                'S',
                'M',
                'L',
                'XL'
            ], output = [];
            deviceIds = deviceIds.split(',');
            deviceIds.forEach(function(item) {
                output.push(devices[item]);
            });

            return output.join(', ');
        });

        $.ajax({
            url: url,
            success: function(response) {
                var source   = $("#entry-template").html(),
                    template = Handlebars.compile(source);

                response.emotions.forEach(function(item, index) {
                    response.emotions[index ].isLandingPage = item.isLandingPage != '0';
                    response.emotions[index ].active = item.active != '0';
                });

                $content.empty().html(template(response));
            }
        });
        {/literal}
    </script>
{/block}