<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Downloads {

    public static function gbwm_downloads()
    {
        ?>
        <div class="wrap gbwm-downloads">

            <h1 class="hideH1"></h1>

            <div class="orders-title bluebg title-font">
                <span><?php _e( 'Manage Downloads', 'woomulti' );?></span>
            </div>

            <?php // messages ?>
            <div class="alert" style="display: none;">
                <span class="closebtn">&times;</span>
                <span class="ajaxMessage"></span>
            </div>

            <p>If you have recentrly started a download and do not see it in the list below then it may still be downloading, depending on how many orders are being downloaded it could take some time, keep refreshing the page or come back later.</p>

            <?php

            // check if our cron has finiahed
            //$search = 'woomulti_create_files_hook';

            $search = 'woomulti_files_retention_hook';

            // set to true to start
            $wmd_cron_finished = true;

            foreach( _get_cron_array() as $firstkey )
            {

                foreach( $firstkey as $key => $value )
                {
                    if( isset( $firstkey[$search] ) ) {
                        $wmd_cron_finished = false;
                        break;
                    }

                }//end foreach

            }// end foreach

            if( $wmd_cron_finished === false )
            {
                echo 'Cron Running<br/><br/>';
            }else{
                echo 'Cron Finished<br/><br/>';
            }

            // start file table container
            ?>

            <button type="submit" name="submit" id="DeleteSelectedFiles" class="button button-primary button-large title-font" value="Delete Selected Files"><?php _e( 'Delete Selected Files', 'woomulti' );?></button>

            <br/><br/>

            <div class="files-container">

                <table class="widefat gbwmtable manage-downloads">
                    <thead>
                    <tr class="gbwm-table-header bluebg title-font">

                        <th class="manage-column column-cb check-column gbwm-checkbox" scope="col">
                            <input type="checkbox" value="" id="cb-select-all">
                        </th>

                        <th scope="col" id="filename" class="manage-column column-primary filename"><?php _e( 'File Name', 'woomulti' );?></th>

                        <th scope="col" id="filedate" class="manage-column filedate"><?php _e( 'Date Created', 'woomulti' );?></th>

                        <th scope="col" id="filetype" class="manage-column filetype"><?php _e( 'File Type', 'woomulti' );?></th>

                        <th scope="col" id="filesize" class="manage-column filesize"><?php _e( 'File Size', 'woomulti' );?></th>

                        <th scope="col" id="actions" class="manage-column column-actions gbwm-file-list-actions"><?php _e( 'Actions', 'woomulti' );?></th>
                    </tr>
                    </thead>

                <tbody>
                    <?php

                    $file = new \FilesystemIterator( GBWM_UPLOADS_PATH, \FilesystemIterator::SKIP_DOTS );

                    $filesCount = new \FilesystemIterator( GBWM_UPLOADS_PATH, \FilesystemIterator::SKIP_DOTS );

                    // total files count
                    $totalFiles = iterator_count( $filesCount );
                    $inum = 0;

                    // used for alternating row background color
                    $c = true;

                    // if there are no results
                    if( $file == '' ){
                    ?>

                        <tr class="format-standard wpautop">
                            <td class="manage-column NoFiles bold center" data-colname="NoFiles" colspan="5"><?php _e( 'No downloads available', 'woomulti' );?></td>
                        </tr>

                    <?php
                    }else{

                        while($file->valid()) {

                            if($file->isFile()){

                                // get the file date / time
                                $cTime = new \DateTime();
                                $cTime->setTimestamp($file->getCTime());

                                // get the file size (human readable)
                                $getFileSize = $file->getSize();

                                if($getFileSize < 1024){

                                    $fileSize = round( $getFileSize );
                                    $sizeLabel = 'B';
                                }

                                if($getFileSize > 1024){
                                    $fileSize = round( $getFileSize / 1000 );
                                    $sizeLabel = 'KB';
                                }

                                if($getFileSize > 1024000){
                                    $fileSize = round( $getFileSize / 1000 );
                                    $sizeLabel = 'MB';
                                }

                                if($getFileSize > 1024000000){
                                    $fileSize = round( $getFileSize / 1000 );
                                    $sizeLabel = 'GB';
                                }

                                // alt row (true,false)
                                $altrow = ( ( $c=!$c )? ' alt-row' : '' );
                                ?>
                                <tr class="format-standard wpautop<?php echo $altrow; ?>">

                                    <td class="gbwm-checkbox">
                                        <input type="checkbox" name="checked[]" value="<?php echo $file->getFileName(); ?>" class="checkbox">
                                    </td>

                                    <td class="manage-column filename" data-colname="filename"><?php echo $file->getFileName();?></td>

                                    <td class="manage-column filedate" data-colname="filedate"><?php echo $cTime->format('Y-m-d h:i:s');?></td>

                                    <td class="manage-column filetype" data-colname="filetype"><?php echo strtoupper( $file->getExtension() );?></td>

                                    <td class="manage-column filesize" data-colname="filesize"><?php echo $fileSize.' '.$sizeLabel;?></td>

                                    <td class="manage-column gbwm-file-list-actions" data-colname="Actions">

                                        <button type="button" class="btn btn-info btn-xs DownloadFile" data-file="<?php echo $file->getFileName();?>"><i class="icon fas fa-download"></i></button>

                                        <button type="button" class="btn btn-danger btn-xs DeleteFile" data-file="<?php echo $file->getFileName();?>"><i class="icon fas fa-trash-alt"></i></button>

                                    </td>

                                </tr>
                        <?php
                            }// end isFile
                        // move to next file
                        $file->next();

                        }// end foreach files as file
                    }// end else
                    ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="6" class="tbfooter title-font"><?php _e( 'Total Files', 'woomulti' );?>: <span class="totalFiles"><?php echo $totalFiles; ?></span></th>
                            </tr>
                        </tfoot>

                    </table>

                <div class="clear"></div>

                <?php // end display table ?>

            </div><?php // end file table container ?>

        </div><?php // end wrap ?>

<?php
    }// end gbwm_downloads function

}// end class