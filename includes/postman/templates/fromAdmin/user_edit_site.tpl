Добрый день!
<br/><br/>
Пользователь <a href='http://iforget.ru/admin.php?module=admins&action=edit&id={$uid}'>{$user_login}</a> изменил данные для своего сайта 
<a href='http://iforget.ru/admin.php?module=admins&action=sayty&uid={$uid}&action2=edit&id={$id}'>{$site_url}</a>
<br/>
{if isset($old['login']) && $old['login'] != $new['login']}
    <b>Логин:</b> {$new['login']} (было: {$old['login']}) <br/>
{/if}
{if isset($old['pass']) && $old['pass'] != $new['pass']}
    <b>Пароль:</b> {$new['pass']} (было: {$old['pass']}) <br/>
{/if}
{if isset($old['url']) && $old['url'] != $new['url']}
    <b>URL:</b> {$new['url']} (было: {$old['url']}) <br/>
{/if}
{if isset($old['url_admin']) && $old['url_admin'] != $new['url_admin']}
    <b>URL админки:</b> {$new['url_admin']} (было: {$old['url_admin']}) <br/>
{/if}
{if isset($old['cena']) && $old['cena'] != $new['cena']}
    <b>Стоимость статьи:</b> {$new['cena']} (было: {$old['cena']}) <br/>
{/if}
{if isset($old['site']) && $old['site_subject'] != $new['site_subject']}
    <b>Тема сайта:</b> {$new['site_subject']} (было: {$old['site_subject']}) <br/>
{/if}
{if isset($old['site_subject_more']) && $old['site_subject_more'] != $new['site_subject_more']}
    <b>Уточнение к тематике:</b> {$new['site_subject_more']} (было: {$old['site_subject_more']}) <br/>
{/if}
{if isset($old['cms']) && $old['cms'] != $new['cms']}
    <b>CMS:</b> {$new['cms']} (было: {$old['cms']}) <br/>
{/if}
{if isset($old['subj_flag']) && $old['subj_flag'] != $new['subj_flag']}
    <b>Тематичность:</b> {$new['subj_flag']} (было: {$old['subj_flag']}) <br/>
{/if}
{if isset($old['obzor_flag']) && $old['obzor_flag'] != $new['obzor_flag']}
    <b>Обзоры:</b> {$new['obzor_flag']} (было: {$old['obzor_flag']}) <br/>
{/if}
{if isset($old['news_flag']) && $old['news_flag'] != $new['news_flag']}
    <b>Новости:</b> {$new['news_flag']} (было: {$old['news_flag']}) <br/>
{/if}
{if isset($old['bad_flag']) && $old['bad_flag'] != $new['bad_flag']}
    <b>Запрещенные темы:</b> {$new['bad_flag']} (было: {$old['bad_flag']}) <br/>
{/if}
{if isset($old['anons_size']) && $old['anons_size'] != $new['anons_size']}
    <b>Размер анонса:</b> {$new['anons_size']} (было: {$old['anons_size']}) <br/>
{/if}
{if isset($old['pic_width']) && $old['pic_width'] != $new['pic_width']}
    <b>Ширина фото:</b> {$new['pic_width']} (было: {$old['pic_width']}) <br/>
{/if}
{if isset($old['pic_height']) && $old['pic_height'] != $new['pic_height']}
    <b>Высота фото:</b> {$new['pic_height']} (было: {$old['pic_height']}) <br/>
{/if}
{if isset($old['pic_position']) && $old['pic_position'] != $new['pic_position']}
    <b>Позиция фото:</b> {$new['pic_position']} (было: {$old['pic_position']}) <br/>
{/if}
{if isset($old['site_comments']) && $old['site_comments'] != $new['site_comments']}
    <b>Пожелания по работе:</b> {$new['site_comments']} (было: {$old['site_comments']}) <br/>
{/if}
{if isset($old['gid']) && $old['gid'] != $new['gid']}
    <b>ID в gogetlinks:</b> {$new['gid']} (было: {$old['gid']}) <br/>
{/if}
{if isset($old['getgoodlinks_id']) && $old['getgoodlinks_id'] != $new['getgoodlinks_id']}
    <b>ID в getgoodlinks:</b> {$new['getgoodlinks_id']} (было: {$old['getgoodlinks_id']}) <br/>
{/if}
{if isset($old['sape_id']) && $old['sape_id'] != $new['sape_id']}
    <b>ID в pr.sape:</b> {$new['sape_id']} (было: {$old['sape_id']}) <br/>
{/if}
{if isset($old['miralinks_id']) && $old['miralinks_id'] != $new['miralinks_id']}
    <b>ID в miralinks:</b> {$new['miralinks_id']} (было: {$old['miralinks_id']}) <br/>
{/if}
{if isset($old['rotapost_id']) && $old['rotapost_id'] != $new['rotapost_id']}
    <b>ID в rotapost:</b> {$new['rotapost_id']} (было: {$old['rotapost_id']}) <br/>
{/if}
{if isset($old['webartex_id']) && $old['webartex_id'] != $new['webartex_id']}
    <b>ID в webartex:</b> {$new['webartex_id']} (было: {$old['webartex_id']}) <br/>
{/if}
{if isset($old['blogun_id']) && $old['blogun_id'] != $new['blogun_id']}
    <b>ID в blogun:</b> {$new['blogun_id']} (было: {$old['blogun_id']}) <br/>
{/if}

<br/>
С уважением,<br/>Администрация проекта iForget.