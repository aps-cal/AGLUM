

<fieldset id="EditIT" style="border-width: 1px; border-style: line; border-color: #000000; ">
    <legend>Equipment Record</legend>
    <form id="equipment" name="equipment" action="/" method="post">
        <input name="ALTag" type="hidden" id="ALTag" />
<table id="tblequipment" style="border: 1px #000000; margin: 2px; " data-popout="false" data-wrapper="false" >
<tbody>
<tr>
<td>Type</td>
<td><input id="txtType" name="Type" type="text" /></td>
<td>Location</td>
<td><input id="txtLocation" name="Location" type="text" /></td>
</tr>
<tr>
<td>Make</td>
<td><input id="txtMake" name="Make" type="text" /></td>
<td>User/Usage</td>
<td><input id="txtUser" name="User" type="text" /></td>
</tr>
<tr>
<td>Model</td>
<td><input id="txtModel" name="Model" type="text" /></td>
<td>Status</td>
<td><input id="txtStatus" name="Status" type="text" /></td>
</tr>
<tr>
<td>Serial_No</td>
<td><input id="txtSerial_No" name="Serial_No" type="text" /></td>
<td>Tag No</td>
<td><input id="txtTag_No" name="Tag_No" type="text" /></td>
</tr>
<tr>
<td>OS</td>
<td><input id="txtOS" name="OS" type="text" /></td>
<td>Computer Name</td>
<td><input id="txtComputer_Name" name="Computer_Name" type="text" /></td>
</tr>
<tr>
<td>ITS Managed</td>
<td><input id="txtITS" name="txtITS" type="text" /></td>
<td>ITS Std Image</td>
<td><input id="txtStd_Image" name="txtStd_Image" type="text" /></td>
</tr>
<tr>
<td>Description</td>
<td colspan="3"><input class="Notes" id="txtDescription" name="txtDescription" type="text" style="width: 100%;" /></td>
</tr>
<tr>
<td>Notes</td>
<td colspan="3"><input class="Notes" id="txtNotes" name="txtNotes" type="text" style="width: 100%;" /></td>
</tr>
<tr></tr>
<tr>
<td nowrap="nowrap">Entered Date/By</td>
<td nowrap="nowrap"><input class="Logging" id="EnteredDate" name="EnteredDate" type="text" /> <input class="Logging" id="EnteredBy" name="EnteredBy" type="text" /></td>
<td nowrap="nowrap">Dumped Date/By</td>
<td nowrap="nowrap"><input class="Logging" id="DumpedDate" name="DumpedDate" type="text" /> <input class="Logging" id="DumpedBy" name="DumpedBy" type="text" /></td>
</tr>
<tr>
<td nowrap="nowrap">Audited Date/By</td>
<td nowrap="nowrap"><input class="Logging" id="CheckedDate" name="CheckedDate" type="text" /> <input class="Logging" id="CheckedBy" name="CheckedBy" type="text" /></td>
<td nowrap="nowrap">Updated Date/By</td>
<td nowrap="nowrap"><input class="Logging" id="UpdatedDate" name="UpdatedDate" type="text" /> <input class="Logging" id="UpdatedBy" name="UpdatedBy" type="text" /></td>
</tr>
<tr>
<td colspan="4"><hr /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td nowrap="nowrap">Quick Actions</td>
<td nowrap="nowrap">
    <input id="New" type="button" value="*New" /> 
    <input id="ToDump" type="button" value="To Dump" /> 
    <input id="Dumped" type="button" value="DUMPED" /> 
    <input id="Move" type="button" value="Move" /> 
    <input id="Edit" type="button" value="Edit" />
</td>
<td align="right"><input id="Cancel" type="button" value="Cancel" /> <input id="Save" type="button" value="SAVE" /></td>
</tr>
</tbody>
</table>
</form>
</fieldset>
<script src="../assets/scripts/jquery-3.3.1.min.js" type="text/javascript"/>
<script type="text/javascript" src="../assets/scripts/itedit.js">
