<form name="computers" id="computers" action="/" method="post">
<input name="ListOrder" type="hidden" id="ListOrder" value="Location" />
<table data-popout="false" data-wrapper="false">
<thead>
<tr><th><input id="New" type="button" value="NEW" />&nbsp;<input id="ExportData" type="button" value="Export" /> [<span id="ITS_User"></span>]</th>
    <th colspan="7">To edit a record select the radio button to left of its status or click [New] to add a new record. 
        The list may be ordered by clicking headings and filtered using drop-downs.</th>
</tr>
<tr id="Heading">                                                                                                                                                                                                        
<th><input type="button" value="Status" onclick="Reorder('Status, Location');" style="width: 100%;" /></th>
<th><input type="button" value="Location" onclick="Reorder('Location,User');" style="width: 100%;" /></th>
<th><input type="button" value="User" onclick="Reorder('User,Location');" style="width: 100%;" /></th>
<th><input type="button" value="Type" onclick="Reorder('Type,Make,Model,Serial_No');" style="width: 100%;" />
</th><th><input type="button" value="Make" onclick="Reorder('Make,Model,Serial_No');" style="width: 100%;" /></th>
<th><input type="button" value="Model" onclick="Reorder('Model,Serial_No');" style="width: 100%;" /></th>
<th><input type="button" value="Serial No" onclick="Reorder('Serial_No');" style="width: 100%;" /></th>
<th><input type="button" value="Device Name" onclick="Reorder('Type,Computer_Name');" style="width: 100%;" /></th>
</tr>
<tr><!--<td>&nbsp;</td>-->
<td><select class="Filter" name="Status" id="Status">
<option></option>
</select></td>
<td><select class="Filter" name="Location" id="Location">
<option></option>
</select></td>
<td><select class="Filter" name="User" id="User">
<option></option>
</select></td>
<td><select class="Filter" name="Type" id="Type">
<option></option>
</select></td>
<td><select class="Filter" name="Make" id="Make">
<option></option>
</select></td>
<td><select class="Filter" name="Model" id="Model">
<option></option>
</select></td>
<td><select class="Filter" name="Serial_No" id="Serial_No">
<option></option>
</select></td>
<td><select class="Filter" name="Computer_Name" id="Computer_Name">
<option></option>
</select></td>
</tr>
</thead>
<tbody id="Computers"></tbody>
<tfoot></tfoot>
</table>
<span id="SQL"></span>
<div id="Message"></div>
</form>        
<script src="../assets/scripts/jquery-3.3.1.min.js" type="text/javascript"/>
<script type="text/javascript" src="../assets/scripts/itlist.js">
