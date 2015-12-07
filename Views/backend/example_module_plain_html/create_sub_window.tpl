{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
<div class="page-header">
    <h1>Collect message from main window</h1>

    <div class="content js-content"></div>
</div>
{/block}

{block name="content/layout/javascript"}
    <script type="text/javascript">
        var subscriber = window.events.subscribe('get-post-message', function(eOpts) {
            var content = document.getElementsByClassName('js-content')[0];

            subscriber.remove();

            if(eOpts.result) {
                content.innerHTML = eOpts.result.msg;
            }
        });
    </script>
{/block}