<?php
	$url = $_POST["inpurl"];	//���͂��ꂽURL
	$csv_data = "";			//�i�[����f�[�^
	$csv_name = "crawl_result.csv"; //csv�̃t�@�C����

	//description,keywords,author�����擾
	$metas = get_meta_tags($url);
	$csv_data = $metas['description'].",";
	$csv_data .= $metas['keywords'].",";
	$csv_data .= $metas['author'].",";

	//URL���HTML�����擾
	$html = file_get_contents($url);
	//���K�\���Ń^�C�g���^�O���擾
	preg_match('/<title>(.*?)<\/title>/',$html,$matches);
	$csv_data .= $matches[1]."\n";

	//���������΍� UTF8�ɂ���
	mb_language('japanese');
	$csv_data = mb_convert_encoding($csv_data,"UTF-8","auto");

	//csv�t�@�C���̍쐬
	$fp = fopen($csv_name,'ab');			//�ǋL���[�h
	flock($fp,LOCK_EX);				//�r�����b�N
	fwrite($fp, pack('C*',0xEF,0xBB,0xBF));		//BOM�΍�
	fwrite($fp,$csv_data);
	fclose($fp);

	//�t�@�C���̏o��
	header('Content-Type: application/octet-stream'); 
	header('Content-Disposition: attachment; filename="'.$csv_name.'"'); 
	readfile($csv_name);

	//�쐬�����t�@�C�����폜����
	unlink($csv_name);

?>
