<{$insNavigation}>

<div>
    <form method='get' name='fselperm' action='perm.php'>
        <table border='0'>
            <tr>
                <td>
                    <select name='permission' onChange='javascript: document.fselperm.submit()'>
                        <option value='1' <{$insSelected.0}> ><{$smarty.const._AM_INSTRUCTION_PERM_VIEW}></option>
                        <option value='2' <{$insSelected.1}> ><{$smarty.const._AM_INSTRUCTION_PERM_SUBMIT}></option>
                        <option value='3' <{$insSelected.2}> ><{$smarty.const._AM_INSTRUCTION_PERM_EDIT}></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type='submit' name='go'>
                </td>
            </tr>
        </table>
    </form>
</div>

<div><{$insFormPerm}></div>
