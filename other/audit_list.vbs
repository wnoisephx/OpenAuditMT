'  Copyright 2003-2015 Opmantek Limited (www.opmantek.com)
'
'  ALL CODE MODIFICATIONS MUST BE SENT TO CODE@OPMANTEK.COM
'
'  This file is part of Open-AudIT.
'
'  Open-AudIT is free software: you can redistribute it and/or modify
'  it under the terms of the GNU Affero General Public License as published 
'  by the Free Software Foundation, either version 3 of the License, or
'  (at your option) any later version.
'
'  Open-AudIT is distributed in the hope that it will be useful,
'  but WITHOUT ANY WARRANTY; without even the implied warranty of
'  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
'  GNU Affero General Public License for more details.
'
'  You should have received a copy of the GNU Affero General Public License
'  along with Open-AudIT (most likely in a file named LICENSE).
'  If not, see <http://www.gnu.org/licenses/>
'
'  For further information on Open-AudIT or for a license other than AGPL please see
'  www.opmantek.com or email contact@opmantek.com
'
' *****************************************************************************

' @package Open-AudIT
' @author Mark Unwin <marku@opmantek.com>
' @version 1.12.6
' @copyright Copyright (c) 2014, Opmantek
' @license http://www.gnu.org/licenses/agpl-3.0.html aGPL v3


' NOTE - make sure you have downloaded RMTSHARE.exe and PSexec.exe from Microsoft and placed them in the "bin" filder.
' see the "bin" folder for instructions (open the text files).

' the number of audits to run concurrently
number_of_audits = 20

' this tells the script to run the audit from this PC or 
' to copy the files to the remote pc and run the script remotely using PSexec

' NOTE - make sure if using the "remote" option that network comms are allowed 
' to be iniated at the remote PC, connecting to the OAv2 host
audit_run_type = "remote"
audit_run_type = "local"

debugging = 3

' the below are needed for remote audits as PSexec takes them as command line arguements
' I don't think (could be wrong) that PSexec can use the local logged on users credentials
' If it can, someone please provide me with a "how to"
' NOTE - if using the "local" option, the below are not needed
' remote_user = "domain\username"
' remote_password = "password"
remote_user = ""
remote_password = ""

Dim script_timeout
script_timeout = 600

' the name and path of the audit script to use
script_name = "c:\xampplite\open-audit\other\audit_windows.vbs"

help = "n"

' update with any submitted command line switches
Set objArgs = WScript.Arguments

For Each strArg in objArgs
    if instr(strArg, "=") then
		varArray = split(strArg, "=")
		select case varArray(0)

			case "audit_run_type"
				audit_run_type = varArray(1)

			case "debugging"
				debugging = varArray(1)

			case "help"
				help = varArray(1)

			case "number_of_audits"
				number_of_audits = varArray(1)

			case "script_name"
				script_name = varArray(1)

			case "remote_user"
				remote_user = varArray(1)

			case "remote_password"
				remote_password = varArray(1)

		end select
	else
		if (strArg = "/?" or strArg = "/help") then
			help = "y"
		end if
	end if
Next 

if (help = "y") then
	wscript.echo "------------------------------"
	wscript.echo "Open-AudIT Domain Audit Script"
	wscript.echo "(c) Opmantek, 2014."
	wscript.echo "------------------------------"
	wscript.echo "This script should be run on a Windows based computer. It queries Active Directory and spawns an audit for each Windows computer found."
	wscript.echo ""
	wscript.echo "Valid command line options are below (items containing * are the defaults) and should take the format name=value (eg: debugging=1)."
	wscript.echo ""
	wscript.echo "  audit_run_type"
	wscript.echo "   *local - Run the audit_windows script from this PC, targetting a remote PC."
	wscript.echo "   remote - Copy the audit_windows script to the remote PC and start it remotely."
	wscript.echo ""
	wscript.echo "  debugging"
	wscript.echo "     0 - No output."
	wscript.echo "     1 - Minimal Output."
	wscript.echo "    *2 - Verbose output."
	wscript.echo ""
	wscript.echo "  /? or help=y"
	wscript.echo "      y - Display this help output."
	wscript.echo "     *n - Do not display this output."
	wscript.echo ""
	wscript.echo "  local_domain"
	wscript.echo "        - The domain you wish to audit. Should be in the format LDAP://your.domain.name"
	wscript.echo ""
	wscript.echo "  number_of_audits"
	wscript.echo "    *25 - The number of concurrently spawned Windows audits."
	wscript.echo ""
	wscript.echo "  script_name"
	wscript.echo "   c:\xampplite\open-audit\other\audit_windows.vbs - The full path to and file name of audit_windows.vbs."
	wscript.echo ""
	wscript.echo "  remote_password"
	wscript.echo "        - The passowrd of the supplied username (if any)."
	wscript.echo ""
	wscript.echo "  remote_user"
	wscript.echo "        - The domain and username credentials used to perform the windows audits. Should be in the format domain/username."
	wscript.echo ""
	wscript.quit
end if

' the array of computer names to audit
pc_array = array ( "COMPUTERNAME1", "COMPUTERNAME2")

' leave the below settings
strComputer = "."
const HKEY_CLASSES_ROOT  = &H80000000
const HKEY_CURRENT_USER  = &H80000001
const HKEY_LOCAL_MACHINE = &H80000002
const HKEY_USERS         = &H80000003
const FOR_APPENDING 	 = 8
const ads_scope_subtree  = 2

set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2") 
set objWMIService2 = GetObject("winmgmts:\\" & strComputer & "\root\WMI")
set oReg = GetObject("winmgmts:{impersonationLevel=impersonate}!\\" & strComputer & "\root\default:StdRegProv")
set objShell = CreateObject("WScript.Shell")
set objFSO = CreateObject("Scripting.FileSystemObject")
set wshNetwork = WScript.CreateObject( "WScript.Network" )

set objlocalwmiservice = getobject("winmgmts:root\cimv2")
set colitems = objlocalwmiservice.execquery("select * from win32_process",,48)
for each objitem in colitems
	if instr (objitem.commandline, wscript.scriptname) <> 0 then
		current_pid = objitem.processid
	end if
next

if audit_run_type = "remote" then
	for i = 0 to ubound(pc_array)
		while num_running > number_of_audits
			if debugging > 0 then wscript.echo("processes running (" & num_running & ") greater than number wanted (" & number_of_audits & ")") end if
			if debugging > 0 then wscript.echo("therefore - sleeping for 4 seconds.") end if
			wscript.sleep 4000
			num_running = HowMany
		wend
		if pc_array(i) <> "" then
			if debugging > 0 then wscript.echo(i & " of " & ubound(pc_array)) end if
			if debugging > 0 then wscript.echo("processes running: " & num_running) end if
			if debugging > 0 then wscript.echo("next system: " & pc_array(i)) end if
			if debugging > 0 then wscript.echo("--------------") end if
			
			remote_location = "\\"& pc_array(i) & "\admin$\"
			objFSO.CopyFile "c:\xampplite\OAv2\other\audit_windows.vbs", remote_location, True
			'objFSO.CopyFile "c:\xampplite\OAv2\other\bin\RMTSHARE.EXE", remote_location, True
			wscript.sleep 4000
			
			Set Command = WScript.CreateObject("WScript.Shell")
			' note - specify -d on the command below to run in non-interactive mode (locally)
			' if you specify -d you will see command windows of the remote processes
			cmd = "c:\xampplite\OAv2\other\bin\psexec.exe \\" & pc_array(i) & " -u " & remote_user & " -p " & remote_password & " -d cscript.exe " & remote_location & "audit_windows.vbs self_delete=y "
			if debugging > 2 then wscript.echo cmd end if
			Command.Run (cmd)
			set Command = nothing
		end if
	next
end if

if audit_run_type = "local" then
	for i = 0 to ubound(pc_array)
		while num_running > number_of_audits
			if debugging > 0 then wscript.echo("processes running (" & num_running & ") greater than number wanted (" & number_of_audits & ")") end if
			if debugging > 0 then wscript.echo("therefore - sleeping for 4 seconds.") end if
			wscript.sleep 4000
			num_running = HowMany
		wend
		if pc_array(i) <> "" then
			if debugging > 0 then wscript.echo(i & " of " & ubound(pc_array)) end if
			if debugging > 0 then wscript.echo("processes running: " & num_running) end if
			if debugging > 0 then wscript.echo("next system: " & pc_array(i)) end if
			if debugging > 0 then wscript.echo("--------------") end if
			command1 = "cscript //nologo """ & script_name & """ " & pc_array(i)
			set sh1=wscript.createobject("wscript.shell")
			sh1.run command1, 6, false
			set sh1 = nothing
			num_running = HowMany
		end if
	next
end if

function HowMany()
	Dim Proc1,Proc2,Proc3
	Set Proc1 = GetObject("winmgmts:{impersonationLevel=impersonate}!\\.\root\cimv2")
	Set Proc2 = Proc1.ExecQuery("select * from win32_process" )
	HowMany=0
	for each Proc3 in Proc2
		If LCase(Proc3.Caption) = "cscript.exe" Then
			HowMany=HowMany + 1
		End If
	next
end function
