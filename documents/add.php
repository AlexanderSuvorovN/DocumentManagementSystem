<?php
    require_once("./../common.php");
    require_once("./../db.php");
    $json = json_decode($_POST['json']);
    // var_dump($_POST);
    dbConnect();
    $fields = ["name", "tags", "folder", "scans", "description"];
    $document_item = [];
    foreach($fields as $f)
    {
        $document_item[$f] = $json->{$f} ?? null;
    }
    $return_code = null;
    $messages = [];
    try
    {
        $dbh->beginTransaction();
        if(!$document_item['name'])
        {
            throw new Exception("Document name can't be empty string.");
        }
        $s = $dbh->prepare("SELECT `id` FROM `dms_documents` WHERE `name` = :document_name LIMIT 1");
        $s->bindParam(":document_name", $document_item['name']);
        $s->execute();
        $fetch = $s->fetchColumn();
        if($fetch !== FALSE)
        {
            throw new Exception("Document '{$document_item['name']}' already exists [id={$fetch}].");
        }
        if($document_item['folder'])
        {
            $s = $dbh->prepare("SELECT `id` FROM `dms_folders` WHERE `label_text` = :folder LIMIT 1");
            $s->bindParam(":folder", $document_item['folder']);
            $s->execute();
            $folder_id = $s->fetchColumn();
            if(!$folder_id)
            {
                throw new Exception("Folder '{$document_item['folder']}' is not defined.");
            }
            $s = $dbh->prepare("INSERT INTO `dms_documents`(`name`, `folder_id`, `description`) VALUES(:name, :folder_id, :description)");
            $s->bindParam(":name", $document_item['name']);
            $s->bindParam(":folder_id", $folder_id);
            $s->bindParam(":description", $document_item['description']);
        }
        else
        {
            $s = $dbh->prepare("INSERT INTO `dms_documents`(`name`, `description`) VALUES(:name, :description)");
            $s->bindParam(":name", $document_item['name']);
            $s->bindParam(":description", $document_item['description']);            
        }
        $s->execute();
        $s = $dbh->prepare("SELECT LAST_INSERT_ID()");
        $s->execute();
        $document_id = $s->fetchColumn();
        if(!$document_id)
        {
            throw new Exception("Can't fetch indentifier of the newly inserted document record.");
        }
        foreach($document_item['tags'] as $tag_item)
        {
            $s = $dbh->prepare("SELECT `id` FROM `dms_tags` WHERE `text` = :tag_item LIMIT 1");
            $s->bindParam(":tag_item", $tag_item);
            $s->execute();
            $tag_id = $s->fetchColumn();
            if(!$tag_id)
            {
                throw new Exception("Tag '{$tag_item}' is not defined.");
            }
            $s = $dbh->prepare("INSERT INTO `dms_documents_to_tags`(`document_id`, `tag_id`) VALUES(:document_id, :tag_id)");
            $s->bindParam(":document_id", $document_id);
            $s->bindParam(":tag_id", $tag_id);
            $s->execute();
        }
        foreach($document_item['scans'] as $scan_filename)
        {
            $create_scan_record = false;
            $s = $dbh->prepare("SELECT `id`, `filename`, `document_id` FROM `dms_documents_scans` WHERE `filename` = :scan_filename LIMIT 1");
            $s->bindParam(":scan_filename", $scan_filename);
            $s->execute();
            $fetch = $s->fetchAll(PDO::FETCH_ASSOC);
            if($fetch === FALSE)
            {
                throw new Exception("Error retrieving information for scan '{$scan_filename}'.");
            }
            else if(count($fetch) === 0)
            {
                if(!file_exists("{$app->config['mainDirPath']}/scans/{$scan_filename}"))
                {
                    throw new Exception("Scan '{$scan_filename}' is not defined and file doesn't exist.");
                }
                else
                {
                    $create_scan_record = true;
                }
            }
            if(!$create_scan_record)
            {
                $scan_item = $fetch[0];
                if($scan_item['document_id'] === null)
                {
                    $s = $dbh->prepare("UPDATE `dms_documents_scans` SET `document_id` = :document_id WHERE `id` = :scan_id LIMIT 1");
                    $s->bindParam(":document_id", $document_id);
                    $s->bindParam(":scan_id", $scan_item['id']);
                    $s->execute();
                    $messages[] = "Scan record for '{$scan_filename}' has been updated.";
                }
                else
                {
                    $s = $dbh->prepare("SELECT `name` FROM `dms_documents` WHERE `id` = :document_id LIMIT 1");
                    $s->bindParam(":document_id", $scan_item['document_id']);
                    $s->execute();
                    $document_name = $s->fetchColumn();
                    throw new Exception("Scan '{$scan_item['filename']}'[id={$scan_item['id']}] is already assigned to another document '$document_name'[id={$scan_item['document_id']}].");
                }
            }
            else
            {
                $s = $dbh->prepare("INSERT INTO `dms_documents_scans`(`filename`, `document_id`) VALUES(:scan_filename, :document_id)");
                $s->bindParam(":scan_filename", $scan_filename);
                $s->bindParam(":document_id", $document_id);
                $s->execute();
                $messages[] = "Scan record for '{$scan_filename}' has been created.";
            }
        }
        $dbh->commit();
        $messages[] = "Successfully added document record '{$document_item['name']}'[id={$document_id}].";
        $return_code = "ok";
    }
    catch(Exception $e)
    {
        $dbh->rollBack();
        $messages[] = "Add document operation has been cancelled: " . $e->getMessage();
        $return_code = "notok";
    }
    dbClose();
    $json = [];
    $json['return_code'] = $return_code;
    $json['messages'] = $messages;
    echo json_encode((object)$json);