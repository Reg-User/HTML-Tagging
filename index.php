<?php
/*functions*/

function trimText($arrayText){
	for ($i = 0; $i < count($arrayText); $i++) {
		$arrayText[$i] = trim($arrayText[$i], " \t\n\r\0\x0B");
	}
	return $arrayText;
}

function iconvText($posttext){
	$iconvtext[0] = iconv_strlen($posttext);//колличество символов в строке с пробелами
	$replacetext = str_replace(" ", "", $posttext);
	$iconvtext[1] = iconv_strlen($replacetext);//колличество символов в строке без пробелов
	return $iconvtext;
}

function iconvPieces($arrayText){
	for ($i = 0; $i < count($arrayText); $i++) {
  	$pie[$i] =  iconv_strlen($arrayText[$i]);
	}
	return $pie;
}

function convertIconv($arrayText){
    for ($i = 0; $i < count($arrayText); $i++) {
        if ($arrayText[$i] <= 90){
            $array[$i] = "h2";
            } elseif (90 <= $arrayText[$i] && $arrayText[$i] <= 130) {
            $array[$i] = "li";
            } else {
            $array[$i] = "p";
            }
        }
    return $array;
}



if (isset($_POST["text"])){
    $iconvText = iconvText($_POST["text"]);//определяем колличество символов
    $textPost = explode("\n", $_POST["text"]);
    $textPost = trimText($textPost);//удаляем ненужные символы
    $texticonv = iconvPieces($textPost);//длинна строк в каждом элементе массива
    $convertIconv = convertIconv($texticonv);//массив из h2, li, p

    //исключаем "oдиночный" li

    if ($convertIconv[0] == 'li' && $convertIconv[1] != 'li'){
        $convertIconv[0] = 'p';
    }
    $countI = count($convertIconv) - 1;
    if ($convertIconv[count($convertIconv) - 1] == 'li' && $convertIconv[count($convertIconv) - 1 - 1] != 'li'){
        $convertIconv[count($convertIconv) - 1] = 'p';
    }
for ($i = 1; $i < count($convertIconv) - 2; $i++) {
    if ($convertIconv[$i] == 'li' && $convertIconv[$i+1] != 'li' && $convertIconv[$i-1] != 'li') {
            $convertIconv[$i] = 'p';
        }
    }



    if ($convertIconv[0] == 'li' && $convertIconv[1] == 'li'){
        $convertIconv[0] = 'ulli';
    }
    if ($convertIconv[count($convertIconv) - 1] == 'li' && $convertIconv[count($convertIconv) - 1 - 1] == 'li'){
        $convertIconv[count($convertIconv) - 1] = 'liul';
    }
    for ($i = 0; $i < count($convertIconv) - 1; $i++) {
        if ($convertIconv[$i] == 'li'){
            if (($convertIconv[$i+1] == 'li' || $convertIconv[$i+1] == 'liul') && $convertIconv[$i-1] != 'li' && $convertIconv[$i-1] != 'ulli'){
        $convertIconv[$i] = 'ulli';
        }
        }
    }
    for ($i = 0; $i < count($convertIconv) - 1; $i++) {
        if ($convertIconv[$i] == 'li' && $convertIconv[$i+1] != 'li' && $convertIconv[$i-1] == 'li') {
            $convertIconv[$i] = 'liul';
        }
    }

    //вставка html тэги
    for ($i = 0; $i < count($convertIconv); $i++) {
        switch ($convertIconv[$i]) {
            case 'ulli':
                $textPost[$i] = '<ul><li>' . $textPost[$i] . '</li>';
                break;
            case 'li':
                $textPost[$i] = '<li>' . $textPost[$i] . '</li>';
                break;
            case 'liul':
                $textPost[$i] = '<li>' . $textPost[$i] . '</li></ul>';
                break;
            case 'p':
                $textPost[$i] = '<p>' . $textPost[$i] . '</p>';
                break;
            case 'h2':
                $textPost[$i] = '<h2>' . $textPost[$i] . '</h2>';
                break;
        }
    }


    $text_echo = implode("\n", $textPost);
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Автодобавление тегов</title>
    </head>
    <body>
    <div style="max-width: 90%; padding-left: 10px; margin: auto;">
        <form name="text-form" method="post">
            <label>
                <textarea name="text" cols="120" rows="20" placeholder="Введите текст"></textarea>
                <br/><br/>
                <input type="submit" value="Подставить теги" />
            </label>
        </form>
                <p>Количество символов с пробелами: <?php echo $iconvText[0];?></p>
        <p>Количество сиволов без пробелов: <?php echo $iconvText[1];?></p>
        <br />

        <label>
            <textarea name="text" cols="120" rows="20" ><?php echo $text_echo;?></textarea><br/>
        </label>
        <div style="max-width: 90%; border: 2px solid red; border-radius: 5px; padding-left: 10px; margin:  15px auto; padding-top: 20px; padding-bottom: 20px;">
            <h2 style="max-width: 90%; border: 2px solid blue; border-radius: 5px; padding-left: 10px; margin: auto; text-align: center">Предварительный просмотр</h2>
            <br />
				<?php echo $text_echo;?>
            </div>
		</div>
    </body>
</html>
