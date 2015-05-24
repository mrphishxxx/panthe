<tr [aktstyle]>
    <td class="add_ticket">
        <a href="?module=admins&action=ticket&action2=create_ticket&uid=[id]"><img src="/images/interface/add.png" /></a>
    </td>
    <td>
        [aktivn]
    </td>
    <td><a href="?module=admins&action=sayty&uid=[id]">[login]</a></td>
    <td>[type]</td>
    <td>[z1]</td>
    <td>[z2]</td>
    <td class="">[balans]</td>
    <td class="tasks none" [not_access_manager]>[total_price]</td>
    <td class="edit"><a href="?module=admins&action=edit&id=[id]" class="ico"></a></td>
    <td class="close">
        <a [not_access_manager] onclick="if (confirm('Вы действительно желаете удалить пользователя')){ location.href='?module=admins&action=del&id=[id]' };return false;" href="#" class="ico"></a>
    </td>
</tr>

