// JavaScript Document

function validate_standardForm(formData, jqForm, options)
{
	return true;
}

function noEmpty(value)
{
    if (value == null || value == '')
    {
        return true;
    }

	return false;
}

function isValidEmail(email, required)
{
    if (required == undefined)  // if not specified, assume it's required
    {
        required = true;
    }
    if (email == null)
    {
        if (required)
        {
            return false;
        }
        return true;
    }
    if (email.length == 0)
    {
        if (required)
        {
            return false;
        }
        return true;
    }
    if (! allValidChars(email))  // check to make sure all characters are valid
    {
        return false;
    }
    if (email.indexOf("@") < 1) //  must contain @, and it must not be the first character
    {
    	return false;
    }
    else if (email.lastIndexOf(".") <= email.indexOf("@"))  // last dot must be after the @
    {
    	return false;
    }
    else if (email.indexOf("@") == email.length)  // @ must not be the last character
    {
    	return false;
    }
    else if (email.indexOf("..") >=0) // two periods in a row is not valid
    {
    	return false;
    }
    else if (email.indexOf(".") == email.length)  // . must not be the last character
	{
    	return false;
    }
    return true;
}

function allValidChars(email)
{
  var parsed     = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";

  for (var i=0; i < email.length; i++)
  {
    var letter = email.charAt(i).toLowerCase();
    if (validchars.indexOf(letter) != -1)
    {
      continue;
    }
    parsed = false;
    break;
  }
  return parsed;
}

function isNumber(str)
{
    var re = /^[-]?\d*\.?\d*$/;
    str = str.toString();

    if (!str.match(re))
    {
        return false;
    }
    return true;
}

function isAlphabet(str)
{
	var alphaExp = /^[a-zA-Z]+$/;

	if(str.match(alphaExp))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function reloadFunction(value)
{
    window.location.replace( sURL+"?page=inicio" );
}