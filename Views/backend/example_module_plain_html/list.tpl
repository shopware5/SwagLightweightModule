{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    <div class="page-header">
        <h1>Suppliers</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th># of products</th>
                </tr>
            </thead>
            <tbody>
                {foreach $suppliers as $supplier}
                    <tr>
                        <td>{if $supplier.image}<img src="{media path={$supplier.image}}" alt="{$supplier.name}" style="height:25px; max-height: 100%">{/if}</td>
                        <td>{$supplier.name}</td>
                        <td>{$supplier.description}</td>
                        <td>{$supplier.articleCounter}</td>
                    </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr class="active">
                    <td colspan="4">
                        <strong>Total:</strong> {$totalSuppliers} supplier(s)
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
{/block}