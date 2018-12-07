//
// Copyright Microsoft 2009
//
// FastCGI Helper Script
//

//
// Constants
//
var VERB_UNKNOWN        = 1;
var VERB_LIST           = 2;
var VERB_SET            = 3;
var VERB_GET            = 4;
var VERB_ADD            = 5;
var VERB_REMOVE         = 6;
var VERB_SYNCINI        = 7;

//
// shell.run constants
//
var SW_HIDDEN           = 0;
var SW_NORMAL           = 1;
var SW_BACKGROUND       = 7;
var WAIT                = true;
var DONT_WAIT           = false;

//
// fso.createtextfile constants
//
var FOR_READING         = 1;
var FOR_WRITING         = 2;
var FOR_APPENDING       = 8;
var OVERWRITE           = true;
var CREATE_IF_NOT_EXIST = true;

//
// Globals
//
var g_Debug             = false;
var g_FSO               = new ActiveXObject( "Scripting.FileSystemObject" );
var g_Shell             = new ActiveXObject( "WScript.Shell" );
var g_Ini               = new Array( );
var g_Comments          = new Array( );
var g_Args              = new Array( );
var g_ArgCount          = 0;
var g_Verb              = VERB_UNKNOWN;
var g_iniPath           = g_Shell.ExpandEnvironmentStrings( "%WinDir%\\system32\\inetsrv\\fcgiext.ini" );
var g_extensionPath     = g_Shell.ExpandEnvironmentStrings( "%WinDir%\\system32\\inetsrv\\fcgiext.dll" );

// IniLoad
// -------
// Loads g_Ini from g_iniPath
function IniLoad( )
{
    LogEcho( "BEGIN: IniLoad" );

    if( !g_FSO.FileExists( g_iniPath ) )
    {
        MyThrow( "Could not find " + g_iniPath );
    }

    var section = "";
    var comments_section = "filestart";
    var commentLineNumber = 0;

    var file = g_FSO.OpenTextFile( g_iniPath, FOR_READING );
    while( !file.AtEndOfStream )
    {
        var line = file.ReadLine( );

        if( line.match( /^\s*$/ ) )
        {
            // 
            // blank line
            //
            continue;
        }

        if(
            ( 0 < line.length && ";" == line.charAt( 0 ) )
        )
        {
            //
            // comment
            //
            if ( IsNotValid (g_Comments[comments_section]))
            {
                g_Comments[comments_section] = new Array( );
            }

            g_Comments[comments_section][commentLineNumber] = line;
            commentLineNumber = commentLineNumber + 1;

            continue;
        }

        //
        // [section]
        //
        var cracked = line.match( /^\[(.*)\]$/ );
        if( null == cracked )
        {
            //
            // name=value, leading/trailing whitespace stripped
            //
            var cracked = line.match( /^\s*([^\s=]*)\s*=\s*(\S.*)\s*$/ );
            if( null == cracked )
            {
                MyThrow( "Unrecognized(1) line in config file: " + line );
            }
            else
            {
                if( "" == section )
                {
                    MyThrow( "Found name=value, expected [section]" );
                }
                if( 3 != cracked.length )
                {
                    MyThrow( "expected: name=value" );
                }
                var cname   = cracked[1];
                var cvalue  = cracked[2];
                if( "Types" != section )
                {
                    cname = StandardCase( cname );
                }
                g_Ini[section][cname] = cvalue;
                // LogEcho( cname + "=" + cvalue );

                comments_section = section + cname;
                commentLineNumber = 0;
            }
        }
        else
        {
            if( 2 != cracked.length )
            {
                MyThrow( "expected: [section]" );
            }
            var section = cracked[1];

            if( section.toUpperCase( ) == "TYPES" )
            {
                //
                // Standard case the types section
                //
                section = "Types";
            }
            if( IsNotValid( g_Ini[section] ) )
            {
                g_Ini[section] = new Array( );
            }

            comments_section = section;
            commentLineNumber = 0;
        }
    }
    file.Close( );

    if( IsNotValid( g_Ini["Types"] ) )
    {
        MyThrow( "Could not find Types section" );
    }

    LogEcho( "END: IniLoad" );
}

// StandardCase
// ------------
// Converts x to camel case, where x is a known setting in unknown case
function StandardCase( x )
{
    x = x.toUpperCase( );

    if( x == "EXEPATH" )
    {
        return "ExePath";
    }
    if( x == "ARGUMENTS" )
    {
        return "Arguments";
    }
    if( x == "QUEUELENGTH" )
    {
        return "QueueLength";
    }
    if( x == "MAXINSTANCES" )
    {
        return "MaxInstances";
    }
    if( x == "IDLETIMEOUT" )
    {
        return "IdleTimeout";
    }
    if( x == "ACTIVITYTIMEOUT" )
    {
        return "ActivityTimeout";
    }
    if( x == "REQUESTTIMEOUT" )
    {
        return "RequestTimeout";
    }
    if( x == "INSTANCEMAXREQUESTS" )
    {
        return "InstanceMaxRequests";
    }
    if( x == "RESPONSEBUFFERLIMIT" )
    {
        return "ResponseBufferLimit";
    }
    if( x == "FLUSHNAMEDPIPE" )
    {
        return "FlushNamedPipe";
    }
    if( x == "IGNOREEXISTINGFILES" )
    {
        return "IgnoreExistingFiles";
    }
    if( x == "IGNOREDIRECTORIES" )
    {
        return "IgnoreDirectories";
    }
    if( x == "PROTOCOL" )
    {
        return "Protocol";
    }
    if( x == "UNHEALTHYONQUEUEFULL" )
    {
        return "UnhealthyOnQueueFull";
    }
    if( x == "ENVIRONMENTVARS" )
    {
        return "EnvironmentVars";
    }
    if( x == "RAPIDFAILSPERMINUTE" )
    {
        return "RapidFailsPerMinute";
    }
    if( x == "MONITORCHANGESTO" )
    {
        return "MonitorChangesTo";
    }
    if( x == "STDERRMODE" )
    {
        return "StderrMode";
    }
    if( x == "SIGNALBEFORETERMINATESECONDS" )
    {
        return "SignalBeforeTerminateSeconds";
    }

    MyThrow( "Unrecognized property: " + x );
}

// IniWrite
// --------
// writes an ini file using g_Ini as input
function IniWrite( )
{
    LogEcho( "BEGIN: IniWrite" );

    var file = g_FSO.OpenTextFile( g_iniPath, FOR_WRITING, CREATE_IF_NOT_EXIST );

    for( var comment in g_Comments["filestart"])   
    {
        file.WriteLine( g_Comments["filestart"][comment])
    } 

    //
    // First write [Types]
    //
    file.WriteLine( "[Types]" );

    for( var comment in g_Comments["Types"])   
    {
        file.WriteLine( g_Comments["Types"][comment])
    } 


    for( var name in g_Ini["Types"] )
    {
        var iniValue = g_Ini["Types"][name];

        if( "" != iniValue )
        {
            file.WriteLine( name + "=" + iniValue );

            for( var comment in g_Comments["Types" + name])   
            {
                file.WriteLine( g_Comments["Types" + name][comment])
            } 
        }
    }
    file.WriteLine( );

    //
    // Now write the rest
    //
    for( var section in g_Ini )
    {
        if( 
            section != "Types" &&
            null != g_Ini[section]
        )
        {
            file.WriteLine( "[" + section + "]" );

            for( var comment in g_Comments[section])   
            {
                file.WriteLine( g_Comments[section][comment])
            }
 
            for( var name in g_Ini[section] )
            {
                var iniValue = g_Ini[section][name];
                if( "" != iniValue )
                {
                    file.WriteLine( name + "=" + iniValue );

                    for( var comment in g_Comments[section + name])   
                    {
                        file.WriteLine( g_Comments[section + name][comment])
                    } 
                }
            }

            file.WriteLine( );
        }
    }

    file.Close( );

    WScript.Echo( "INI successfully written." );

    LogEcho( "END: IniWrite" );
}

// ParseCommandLine
// ----------------
// returns true for a successful parse
function ParseCommandLine( )
{
    LogEcho( "BEGIN: ParseCommandLine" );

    if( 0 == WScript.Arguments.length )
    {
        return false;
    }

    //
    // Produce g_Args, a name-value array
    //
    for( var i = 0; i < WScript.Arguments.length; i++ )
    {
        var arg = WScript.Arguments( i );

        //
        // fcgiconfig -?
        // fcgiconfig /help
        //
        if( 
            arg.match( /help/ ) ||
            arg.match( /\?/ )
        )
        {
            return false;
        }

        //
        // All args must start with '/' or '-'
        //
        if( "/" != arg.charAt( 0 ) && "-" != arg.charAt( 0 ) )
        {
            WScript.Echo( "Must use '/' or '-' to separate arguments" );
            return false;
        }
        arg = arg.substr(1);
    
        var aname = "";
        var avalue = "";

        var match = arg.match( /([^:]*):(.*)/ );
        if( null == match )
        {
            aname = arg;
        }
        else
        {
            aname = match[1];
            avalue = match[2];
        }

        if( aname.toUpperCase( ) == "SECTION" )
        {
            aname = "section";
        }

        if( !IsNotValid( g_Args[aname] ) )
        {
            WScript.Echo( "Duplicate argument: " + aname );
            return false;
        }

        g_Args[aname] = avalue;
        g_ArgCount++;
    }

    //
    // try determine verb
    //
    var verb;
    for( var name in g_Args )
    {
        verb = VERB_UNKNOWN;

        if(     
            name.toUpperCase( ) == "LIST" ||
            name.toUpperCase( ) == "ENUM" 
        )
        {
            verb = VERB_LIST;
        }
        if( name.toUpperCase( ) == "SET" )
        {
            verb = VERB_SET;
        }
        if( name.toUpperCase( ) == "GET" )
        {
            verb = VERB_GET;
        }
        if( name.toUpperCase( ) == "ADD" )
        {
            verb = VERB_ADD;
        }
        if( name.toUpperCase( ) == "REMOVE" )
        {
            verb = VERB_REMOVE;
        }
        if( name.toUpperCase( ) == "SYNCINI" )
        {
            verb = VERB_SYNCINI;
        }

        if( verb != VERB_UNKNOWN )
        {
            if( g_Verb != VERB_UNKNOWN )
            {
                WScript.Echo( "Error: Multiple verbs" );
                return false;
            }
            g_Verb = verb;
        }
    }
    if( VERB_UNKNOWN == g_Verb )
    {
        WScript.Echo( "Error: Unknown verb" );
        return false;
    }

    LogEcho( "END: ParseCommandLine" );
    return true;
}

// PrintUsage
// ----------
//
function PrintUsage( )
{
    WScript.Echo( "Usage:" );
    WScript.Echo( "    List settings:" );
    WScript.Echo( "        fcgiconfig.js -list [-section:\"foo\"]" );
    WScript.Echo( "    Set/Delete setting:" );
    WScript.Echo( "        fcgiconfig.js -set -section:\"foo\" -<property>[:value]" );
    WScript.Echo( "    Get setting:" );
    WScript.Echo( "        fcgiconfig.js -get -section:\"foo\" -<property>" );
    WScript.Echo( "    Add new script mapping:" );
    WScript.Echo( "        fcgiconfig.js -add -section:\"foo\" -extension:\"bar\" -path:\"<path>\" [-site:NNN] [-application:\"/w3svc/<siteid>/root/<appname>\"" );
    WScript.Echo( "    Remove a script mapping:" );
    WScript.Echo( "        fcgiconfig.js -remove -section:\"foo\"" );
    WScript.Echo( "    Apply pre-existing ini settings to metabase:" );
    WScript.Echo( "        fcgiconfig.js -syncini" );
}

// FindSection
// -----------
// case-insensitive search for seekSection
function FindSection( seekSection )
{
    var returnMe = null;

    LogEcho( "BEGIN: FindSection " + seekSection );

    if( IsNotValid( seekSection ) )
    {
        MyThrow( "Invalid section to seek, seekSection=" + seekSection );
    }

    seekSection = seekSection.toUpperCase( );
    for( var section in g_Ini )
    {
        if( seekSection == section.toUpperCase( ) )
        {
            returnMe = g_Ini[section];
        }
    }

    LogEcho( "END: FindSection, returnMe null=" + ( returnMe == null ) );

    return returnMe;
}

// VerbIniList
// -----------
// handles the ini list verb
function VerbIniList( )
{
    LogEcho( "BEGIN: VerbIniList" );

    if( 
        0 == g_ArgCount ||
        g_ArgCount > 2
    )
    {
        PrintUsage( );
        return;
    }

    if( 1 == g_ArgCount )
    {
        //
        // Dump all
        //

        WScript.Echo( "[Types]" );
        for( var name in g_Ini["Types"] )
        {
            WScript.Echo( name + "=" + g_Ini["Types"][name] );
        }
        
        for( var section in g_Ini )
        {
            if( "Types" != section )
            {
                WScript.Echo( "[" + section + "]" );
                for( var name in g_Ini[section] )
                {
                    WScript.Echo( name + "=" + g_Ini[section][name] );
                }
            }
        }
    }
    else
    {
        //
        // Dump a specific section
        //
        var section = FindSection( g_Args["section"] );

        if( null == section )
        {
            WScript.Echo( "Section not found, section=" + g_Args["section"] );
        }
        else
        {
            WScript.Echo( "[" + g_Args["section"] + "]" );
            for( var name in section )
            {
                WScript.Echo( name + "=" + section[name] );
            }
        }
    }

    LogEcho( "END: VerbIniList" );
}

// VerbIniSet
// ----------
// handles the ini set verb
function VerbIniSet( )
{
    LogEcho( "BEGIN: VerbIniSet" );

    if( 
        3 != g_ArgCount ||
        IsNotValid( g_Args["section"] ) 
    )
    {
        PrintUsage( );
        return;
    }

    var section = FindSection( g_Args["section"] );
    if( null == section )
    {
        WScript.Echo( "Section not found, section=" + g_Args["section"] );
    }
    else
    {
        var count = 0;
        for( var key in g_Args )
        {
            if( 
                key.toUpperCase( ) != "SET" &&
                key.toUpperCase( ) != "SECTION"
            )
            {
                var normalName = StandardCase( key );
                section[normalName] = g_Args[key];
                count++;
            }
        }
        if( 1 != count )
        {
            MyThrow( "Expected exactly one property to set" );
        }

        IniWrite( );
    }
    
    LogEcho( "END: VerbIniSet" );
}

// VerbIniGet
// ----------
// handles the ini get verb
function VerbIniGet( )
{
    LogEcho( "BEGIN: VerbIniGet" );

    if( 
        3 != g_ArgCount ||
        IsNotValid( g_Args["section"] ) 
    )
    {
        PrintUsage( );
        return;
    }

    var section = FindSection( g_Args["section"] );
    if( null == section )
    {
        WScript.Echo( "Section not found, section=" + g_Args["section"] );
    }
    else
    {
        var count = 0;
        for( var key in g_Args )
        {
            if( 
                key.toUpperCase( ) != "GET" &&
                key.toUpperCase( ) != "SECTION"
            )
            {
                var normalName = StandardCase( key );
                if( IsNotValid( section[normalName] ) )
                {
                    WScript.Echo( "Not set: " + normalName );
                }
                else
                {
                    WScript.Echo( normalName + "=" + section[normalName] );
                }
                count++;
            }
        }
        if( 1 != count )
        {
            MyThrow( "Expected exactly one property to get" );
        }
    }
    LogEcho( "END: VerbIniGet" );
}

// AddScriptMap
// ------------
// Add a script map. if apppath="", do it globally
function AddScriptMap( apppath, extension )
{
    var mappingExists = false;

    LogEcho( "BEGIN: AddScriptMap" );

    //
    // Load scriptmaps
    //
    var path = "IIS://localhost";
    if( apppath != "" )
    {
        path += apppath;
    }
    else
    {
        path += "/W3SVC";
    }

    var node = null;
    try
    {
        node = GetObject( path );
    }
    catch( e )
    {
        LogEcho( 
            "failed to GetObject, error=" + e.number + ", description=\"" + e.description + "\", path=" + path 
        );
        throw e;
    }
    var vbScriptMaps = node.ScriptMaps;
    var scriptmaps = new VBArray( vbScriptMaps ).toArray( );

    //
    // Check for duplicates
    //
    var extRegex = "^." + extension + ",";
    for( var i = 0; i < scriptmaps.length; i++ )
    {
        var re = new RegExp( extRegex, "i" );
        if( re.test( scriptmaps[i] ) )
        {
            //
            // ScriptMap already exists, this is ok if it points to our extension already
            //
            var scriptMapUpper = scriptmaps[i].toUpperCase( );
            if( -1 == scriptMapUpper.indexOf( g_extensionPath.toUpperCase( ) ) )
            {
                MyThrow( "ScriptMap already exists, path=" + path + ", scriptmap=" + scriptmaps[i] );
            }
            else
            {
                mappingExists = true;
            }
        }
    }

    //
    // Add scriptmap
    //
    if( !mappingExists )
    {
        //
        // Remove file exists check on XP
        // In case of error, use scriptMapFlags = 5
        //
        var scriptMapFlags = "5";
        try
        {
            var majorVersion = g_Shell.RegRead( "HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\InetStp\\MajorVersion" );
            if( !IsNotValid( majorVersion ) )
            {
                //
                // 1 means no file exists check
                // 5 means do file exists check
                //
                scriptMapFlags = ( majorVersion == 5 ) ? "1" : "5";
            }
        }
        catch( e )
        {
            scriptMapFlags = "5";
        }

        scriptmaps[scriptmaps.length] = "." + extension + "," + g_extensionPath + "," + scriptMapFlags + ",GET,HEAD,POST";
    }

    //
    // convert to vb array
    //
    var dictionary = new ActiveXObject( "Scripting.Dictionary" );
    for( var i = 0; i < scriptmaps.length; i++ )
    {
        dictionary.add( i, scriptmaps[i] );
    }
    node.ScriptMaps = dictionary.Items( );

    //
    // commit iis settings
    //
    node.SetInfo( );

    LogEcho( "END: AddScriptMap" );
}

// VerbIniAdd
// ----------
// verb add
function VerbIniAdd( )
{
    LogEcho( "BEGIN: VerbIniAdd" );
    
    if( 
        ( 4 != g_ArgCount && 5 != g_ArgCount && 6 != g_ArgCount) ||
        IsNotValid( g_Args["section"] ) 
    )
    {
        PrintUsage( );
        return;
    }

    //
    // Extract information
    //
    var section     = g_Args["section"];
    var extension   = "";
    var apppath     = "";
    var site        = 0;
    var path        = "";
    var count       = 0;

    for( var key in g_Args )
    {
        var testKey = key.toUpperCase( );
        if( testKey == "EXTENSION" )
        {
            extension = g_Args[key];
        }
        if( testKey == "PATH" )
        {
            path = g_Args[key];
        }
        if( testKey == "APPLICATION" )
        {
            apppath = g_Args[key];
        }
        if( testKey == "SITE" )
        {
            site = parseInt( g_Args[key] );
        }
        count++;
    }

    //
    // validate args
    //
    if( "" == path )
    {
        MyThrow( "Missing path argument" );
    }
    if( "" == extension )
    {
        MyThrow( "Missing extension argument" );
    }
    if( site != 0 && apppath != "" )
    {
        MyThrow( "Parameters -site and -application cannot be used together" );
    }
    if( isNaN( site ) )
    {
        MyThrow( "Site argument is not a number" );
    }

    if( apppath != "" )
    {
        //
        // Standardize application path
        //
        apppath = apppath.toUpperCase( );

        if( apppath.indexOf( "/" ) == 0 )
        {
            apppath = apppath.substring( 1 );
        }

        if( apppath.indexOf( "LM/" ) == 0 )
        {
            apppath = apppath.substring( 3 );
        }

        if( apppath.indexOf( "W3SVC/" ) == 0 )
        {
            apppath = apppath.substring( 6 );
        }

        if( apppath.indexOf( "/" ) == -1 )
        {
            apppath += "/ROOT";
        }
        else if( apppath.indexOf( "/ROOT" ) == -1 )
        {
            apppath += "ROOT";
        }
        else if( apppath.charAt( apppath.length - 1 ) == '/' )
        {
            apppath = apppath.substring( 0, apppath.length - 1 );
        }

        apppath = "/W3SVC/" + apppath;
    }

    //
    // check for dupe section name
    //
    if( null != FindSection( section ) )
    {
        MyThrow( "Section already exists, section=" + section );
    }

    //
    // check for dupe mapping
    //
    var map = extension;
    if( 0 != site )
    {
        map = map + ":" + site;
    }
    else if( "" != apppath )
    {
        map = map + ":" + apppath;
    }

    for( var key in g_Ini["Types"] )
    {
        if( map.toUpperCase( ) == key.toUpperCase( ) )
        {
            MyThrow( "Mapping already exists: key=" + key );
        }
    }

    //
    // Finally update ini
    //
    g_Ini["Types"][map] = section;
    g_Ini[section] = new Array( );
    g_Ini[section]["ExePath"] = path;

    //
    // In memory INI up to date, now update IIS
    //
    if( apppath != "" )
    {
        AddScriptMap( apppath, extension );
    }
    else if( site != 0 )
    {
        AddScriptMap( "/W3SVC/" + site + "/Root", extension );
    }
    else
    {
        AddScriptMap( "", extension );
    }

    //
    // commit ini settings
    //
    IniWrite( );

    LogEcho( "END: VerbIniAdd" );
}

// VerbIniRemove
// -------------
// verb remove
function VerbIniRemove( )
{
    var removeExtension   = "";
    var removeSite        = 0;
    var removeApplication = "";

    LogEcho( "BEGIN: VerbIniRemove" );

    if( 
        (2 != g_ArgCount && 3 != g_ArgCount) || 
        IsNotValid( g_Args["section"] ) 
    )
    {
        PrintUsage( );
        return;
    }

    if( null == FindSection( g_Args["section"] ) )
    {
        WScript.Echo( "Section not found, section=" + g_Args["section"] );
        return;
    }

    //
    // Remove [Types] with section name target, throw if not found
    //
    var newTypes = new Array( );
    var found = false;
    for( var key in g_Ini["Types"] )
    {
        var type = g_Ini["Types"][key];
        if( type.toUpperCase( ) != g_Args["section"].toUpperCase( ) )
        {
            newTypes[key] = type;
        }
        else
        {
            if( found )
            {
                MyThrow( 
                    "This script does not currently support multiple [Types] entries pointing to a single [section]" 
                );
            }
            found = true;

            removeExtension = key;
            var match = removeExtension.match( /^(.*):(.*)$/ );
            if( null != match )
            {
                //
                // site id present
                //
                removeExtension = match[1];
                removeSite      = parseInt( match[2] );

                if( isNaN( removeSite ) )
                {
                    removeSite = 0;
                    removeApplication = match[2];
                }
            }
        }
    }
    if( !found )
    {
        MyThrow( "could not find section in [Types], section=" + g_Args["section"] );
    }
    g_Ini["Types"] = newTypes;

    //
    // Remove [section]
    //
    for( var key in g_Ini )
    {
        if( key.toUpperCase( ) == g_Args["section"].toUpperCase( ) )
        {
            g_Ini[key] = null;
        }
    }

    //
    // In memory INI up to date, now update IIS
    //

    //
    // Load scriptmaps
    //
    if( "" == removeExtension )
    {
        MyThrow( "Extension not found for removal" );
    }
    var path = "IIS://localhost";

    if( removeApplication != "" )
    {
        path += removeApplication;
    }
    else if( removeSite != 0 )
    {
        path += "/W3SVC/" + removeSite + "/ROOT";
    }
    else
    {
        path += "/W3SVC";
    }

    var node = null;
    try
    {
        node = GetObject( path );
    }
    catch( e )
    {
        WScript.Echo( 
            "failed to GetObject, error=" + e.number + ", description=\"" + e.description + "\", path=" + path 
        );
        throw e;
    }
    var vbScriptMaps = node.ScriptMaps;
    var scriptmaps = new VBArray( vbScriptMaps ).toArray( );

    //
    // Remove scriptmap
    //
    var found = false;
    var dictionary = new ActiveXObject( "Scripting.Dictionary" );
    var nextInsert = 0;
    var extRegex = "^." + removeExtension + ",";
    for( var i = 0; i < scriptmaps.length; i++ )
    {
        var re = new RegExp( extRegex, "gi" );
        if( re.test( scriptmaps[i] ) )
        {
            if( found )
            {
                MyThrow( "Unexpected to find multiple scriptmaps" );
            }
            found = true;
        }
        else
        {
            dictionary.add( nextInsert, scriptmaps[i] );
            nextInsert++;
        }
    }
    if( !found )
    {
        MyThrow( "Could not find scriptmap to remove" );
    }

    node.ScriptMaps = dictionary.Items( );

    //
    // commit iis settings
    //
    node.SetInfo( );
    
    IniWrite( );

    LogEcho( "END: VerbIniRemove" );
}

// VerbIniSync
// -----------
// Updates the metabase based on ini settings. Sometimes, e.g. after uninstall ini will still contain mappings, but
// metabase will not.
function VerbIniSync( )
{
    LogEcho( "BEGIN: VerbIniSync " );

    for( var name in g_Ini["Types"] )
    {
        if( "" != name )
        {
            var site = 0;
            var ext  = "";
            var app  = "";

            var match = name.match( /^(.*):(.*)$/ );
            if( null != match )
            {
                ext     = match[1];
                site    = parseInt( match[2] );

                if( isNaN( site ) )
                {
                    site = 0;
                    app = match[2];
                }
            }
            else
            {
                ext = name;
            }

            try
            {
                if( app != "" )
                {
                    AddScriptMap( app, ext );
                }
                else if( site != 0 )
                {
                    AddScriptMap( "/W3SVC/" + site + "/ROOT", ext );
                }
                else
                {
                    AddScriptMap( "", ext );
                }
            }
            catch( e ) 
            {
                var description = e.description + "";
                if( 
                    -2147024893 != e.number &&
                    !description.match( /scriptmap already exists/i )
                )
                {
                    //
                    // swallow expected/known errors, keep going
                    //
                    throw e;
                }
                else
                {
                    LogEcho( "Ignoring {" + description + "} for ext=" + ext + ", site=" + site );
                }
            }
        }
    }

    LogEcho( "END: VerbIniSync " );
}

// CscriptHost
// -----------
// if wscript: pops an error and returns false
function CscriptHost( )
{
    LogEcho( "BEGIN: CscriptHost" );

    if( WScript.FullName.match( /wscript/i ) )
    {
        WScript.Echo( 
            WScript.ScriptFullName + " can only be run using cscript." +
            " Either prepend cscript.exe (e.g. \"cscript.exe " + WScript.ScriptFullName 
            + "\"), or make cscript the default interpretor (e.g. \"cscript //h:cscript\")" );
        return false;
    }

    LogEcho( "END: CscriptHost" );

    return true;
}

// IisInstalled
// ------------
// cheesy check - looks for inetinfo.exe
function IisInstalled( )
{
    LogEcho( "BEGIN: IisInstalled" );

    var inetinfo = g_Shell.ExpandEnvironmentStrings( "%WinDir%\\system32\\inetsrv\\inetinfo.exe" );
    if( !g_FSO.FileExists( inetinfo ) )
    {
        WScript.Echo( "IIS does not seem to be installed" );
        return false;
    }

    LogEcho( "END: IisInstalled" );

    return true;
}

// IsWow64
// -------
// returns true if running under wow64
function IsWow64( )
{
    LogEcho( "BEGIN: IsWow64" );

    var processor   = g_Shell.ExpandEnvironmentStrings( "%PROCESSOR_ARCHITECTURE%" );
    var expandMe    = "%PROCESSOR_ARCHITEW6432%";
    var wow         = g_Shell.ExpandEnvironmentStrings( expandMe );

    if(     
        wow != expandMe &&
        processor.match( /86/ ) 
    )
    {
        WScript.Echo( "Running under WoW64 is not supported, please run from a 64-bit cmd.exe/cscript.exe" );
        return true;
    }

    LogEcho( "END: IsWow64" );

    return false;
}

// Main
// ----
//
function Main( )
{
    if( !CscriptHost( ) )
    {
        return;
    }

    if( IsWow64( ) )
    {
        return;
    }

    if( !IisInstalled( ) )
    {
        return;
    }

    if( !ParseCommandLine( ) )
    {
        PrintUsage( );
        return;
    }

    IniLoad( );

    switch( g_Verb )
    {
    case VERB_LIST:
        VerbIniList( );
        break;
    case VERB_SET:
        VerbIniSet( );
        break;
    case VERB_GET:
        VerbIniGet( );
        break;
    case VERB_ADD:
        VerbIniAdd( );
        break;
    case VERB_REMOVE:
        VerbIniRemove( );
        break;
    case VERB_SYNCINI:
        VerbIniSync( );
        break;
    default:
        PrintUsage( );
        MyThrow( "Unrecognized argument(s)" );
        break;
    }
}

// LogEcho
// -------
// prints logging information useful for debugging
function LogEcho( x )
{
    if( g_Debug )
    {
        WScript.Echo( new Date( ) + " - " + x );
    }
}

// IsNotValid
// ----------
// the various invalid states of a variable, copied from runstress
function IsNotValid(variable) {
    // JScript considers "undefined" the same as null.
    // JScript considers "" the same as 0
    // JScript considers NaN the same as variable != variable
    try {
        return( 
            variable == null    ||
            variable === ""     ||
            variable != variable
        );
    } 
    catch( e ) 
    { 
        //some objects don't have toString, it seems...
        return (variable == null);
    }
}

// MyThrow
// -------
// Regular throwing doesn't include a stack, making it more difficult to debug exceptions.
// This throw includes a stack
function MyThrow( x )
{
    DumpStack( );
    var e           = new Error( );
    e.description   = x;
    e.number        = -33;
    throw e;
}

// DumpStack
// ---------
// Does a stack backtrace
function DumpStack( )
{
    LogEcho( "Stack Backtrace: " );

    var me      = arguments.callee;
    var down    = me.caller;

    while( null != down )
    {
        var match = down;
        match = match + "";
        match = match.match( /function ([^ ]*)\(/ );
        if( null == match )
        {
            LogEcho( "no match" );
        }
        else
        {
            LogEcho( match[1] );
        }
        down = down.caller;
    }
}

Main( );
