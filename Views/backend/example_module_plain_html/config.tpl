{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    <div class="page-header">
        <h1>Plugin Config Form</h1>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{$data.label} ({$data.name})</h3>
        </div>
        <div class="panel-body">

            {if $data.description}
                <blockquote>
                    <p>{$data.description}</p>
                </blockquote>
            {/if}

            <form class="form-horizontal">
                {foreach $data.elements as $element}
                    <div class="form-group">
                        {if $element.type == 'checkbox'}
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"{if $element.value == 1} checked="checked"{/if}> {$element.label}
                                    </label>
                                </div>
                            </div>
                        {else}
                            <label class="col-sm-2 control-label" for="field-{$element.id}">{$element.label}</label>
                            <div class="col-sm-10">
                                {if $element.type == 'select'}
                                    <select class="form-control" id="field-{$element.id}">
                                        {foreach $element.options.store as $entry}
                                            <option value="{$entry.0}"{if $entry@index == $element.value} selected="selected"{/if}>{$entry.1}</option>
                                        {/foreach}
                                    </select>
                                {else}
                                    <input type="{$element.type}" class="form-control" id="field-{$element.id}" value="{$element.value}">
                                {/if}
                            </div>
                        {/if}
                    </div>
                {/foreach}
            </form>
        </div>
        <div class="panel-footer clearfix">
            <div class="pull-right">
                <button type="button" class="btn btn-success">Save form (actually don't save the form)</button>
            </div>
        </div>
    </div>
{/block}