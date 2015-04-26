<?php require_once ( $config['ftp_views'] . '/header.php' ); ?>

    <div class="row">
        <h1 class="text-center">TradeTracker Assignment<br/><small>XML Product Feed Parser</small></h1>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-wrapper">
                <form name="frmFeed" action="<?php echo $config['http_base'];?>index/parse" method="post">
                    <div class="form-group">
                        <label for="feedUrl">Feed URL</label>
                        <input type="text" class="form-control" id="feedUrl" name="feedUrl" placeholder="Enter Feed URL">
                    </div>
                    <button type="submit" id="parse" class="btn btn-default">Parse</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 <?php echo $class; ?>">
            <div id="response" class="<?php echo $class_type; ?>" role="alert">
                <?php
                    if(isset($message)) {
                        echo $message;
                    }

                    $this->deleteSession('error');
                    $this->deleteSession('success');
                    $this->deleteSession('products');
                ?>
            </div>
        </div>
    </div>

<?php require_once ( $config['ftp_views']. '/footer.php' ); ?>