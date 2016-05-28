<?php
/**
 *	@package JAMA
 *
 *	Error handling
 *	@author Michael Bommarito
 *	@version 01292005
 */

//Language constant
define('JAMALANG', 'EN');


//All errors may be defined by the following format:
//define('ExceptionName', N);
//$error['lang'][ExceptionName] = 'Error message';
$error = array();

/*
I've used Babelfish and a little poor knowledge of Romance/Germanic languages for the translations here.
Feel free to correct anything that looks amiss to you.
*/

define('PolymorphicArgumentException', -1);
$error['EN'][PolymorphicArgumentException] = "Invalid argument pattern for polymorphic function.";
$error['FR'][PolymorphicArgumentException] = "Mod�le inadmissible d'argument pour la fonction polymorphe.".
$error['DE'][PolymorphicArgumentException] = "Unzul�ssiges Argumentmuster f�r polymorphe Funktion.";

define('ArgumentTypeException', -2);
$error['EN'][ArgumentTypeException] = "Invalid argument type.";
$error['FR'][ArgumentTypeException] = "Type inadmissible d'argument.";
$error['DE'][ArgumentTypeException] = "Unzul�ssige Argumentart.";

define('ArgumentBoundsException', -3);
$error['EN'][ArgumentBoundsException] = "Invalid argument range.";
$error['FR'][ArgumentBoundsException] = "Gamme inadmissible d'argument.";
$error['DE'][ArgumentBoundsException] = "Unzul�ssige Argumentstrecke.";

define('MatrixDimensionException', -4);
$error['EN'][MatrixDimensionException] = "Matrix dimensions are not equal.";
$error['FR'][MatrixDimensionException] = "Les dimensions de Matrix ne sont pas �gales.";
$error['DE'][MatrixDimensionException] = "Matrixma�e sind nicht gleich.";

define('PrecisionLossException', -5);
$error['EN'][PrecisionLossException] = "Significant precision loss detected.";
$error['FR'][PrecisionLossException] = "Perte significative de pr�cision d�tect�e.";
$error['DE'][PrecisionLossException] = "Bedeutender Pr�zision Verlust ermittelte.";

define('MatrixSPDException', -6);
$error['EN'][MatrixSPDException] = "Can only perform operation on symmetric positive definite matrix.";
$error['FR'][MatrixSPDException] = "Perte significative de pr�cision d�tect�e.";
$error['DE'][MatrixSPDException] = "Bedeutender Pr�zision Verlust ermittelte.";

define('MatrixSingularException', -7);
$error['EN'][MatrixSingularException] = "Can only perform operation on singular matrix.";

define('MatrixRankException', -8);
$error['EN'][MatrixRankException] = "Can only perform operation on full-rank matrix.";

define('ArrayLengthException', -9);
$error['EN'][ArrayLengthException] = "Array length must be a multiple of m.";

define('RowLengthException', -10);
$error['EN'][RowLengthException] = "All rows must have the same length.";

/**
 *	Custom error handler
 *	@param int $num Error number
 */
function JAMAError($errorNumber = null) {
	global $error;

	if (isset($errorNumber)) {
		if (isset($error[JAMALANG][$errorNumber])) {
			return $error[JAMALANG][$errorNumber];
		} else {
			return $error['EN'][$errorNumber];
		}
	} else {
		return ("Invalid argument to JAMAError()");
	}
}
