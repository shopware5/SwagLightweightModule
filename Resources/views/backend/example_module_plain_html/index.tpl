{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    <div class="page-header">
        <h1><code>postMessage</code>-API</h1>
    </div>
    <p>The following examples shows off the bidirectional communication between the frame and the Shopware administration.</p>

    <div class="page-header">
        <h2>Module window operations</h2>
    </div>

    <div class="btn-group" role="group" aria-label="...">
        <button type="button" class="btn btn-default btn-minimize">Minimize</button>
        <button type="button" class="btn btn-default btn-maximize">Maximize</button>
        <button type="button" class="btn btn-default btn-show">Show</button>
        <button type="button" class="btn btn-default btn-hide">Hide</button>
        <button type="button" class="btn btn-danger btn-destroy">Destroy</button>
        <button type="button" class="btn btn-default btn-subwindow">Test subwindow</button>
    </div>

    <br/>
    <br/>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Set the window title</h3></div>
        <div class="panel-body">

            <form class="form-horizontal title-form">
                <div class="form-group">
                    <label for="windowTitle" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="windowTitle" name="title" required placeholder="Your title...">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Set title</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Get window width</h3></div>
        <div class="panel-body">

            <form class="form-horizontal get-window-width-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Window width</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control" name="window-width" placeholder="Fetch the window width...">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Fetch window width</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Set window width</h3></div>
        <div class="panel-body">

            <form class="form-horizontal set-window-width-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Window width</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="window-width" required placeholder="Your window width..." min="300" max="1920">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Set window width</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Get window height</h3></div>
        <div class="panel-body">

            <form class="form-horizontal get-window-height-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Window height</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control" name="window-height" placeholder="Fetch the window height...">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Fetch window height</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Set window height</h3></div>
        <div class="panel-body">

            <form class="form-horizontal set-window-height-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Window height</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="window-height" required placeholder="Your window height..." min="300" max="1000">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Set window height</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Open backend module</h3></div>

        <div class="panel-body">
            <form class="form-horizontal open-module-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Module name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="module-name" required placeholder="Insert module name...">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Open module</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Create sub window</h3></div>

        <div class="panel-body">
            <form class="form-horizontal open-subwindow-form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">URL</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="url" required placeholder="Insert window url...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Window Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" required placeholder="Insert window title...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Technical name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="techName" required placeholder="Insert technical title...">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Open sub window</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
{/block}
