<?php

/**
 * @THIS is collection of carbon stock estimation equation
 * authors, references and other information is given below
 * @Developer Rey Mark Balaod
 * @distribution ALL RIGHT RESERVED ©2024
 **/


namespace app\models;

class carbon
{
    /**
     * @param int $DBH - tree diameter at Breast height measurement'
     * @param int $D - tree diameter at breast height measurement
     * @param int $H - tree total height measurement
     * @return float
     */

    public static function brown1997($DBH)
    {
        return exp(-2.134 + 2.530 * log($DBH));//unit: kg
    }

    public static function brown1989_1($DBH)
    {
        return 42.69 - 12.800 * $DBH + 1.242 * pow($DBH, 2);//unit: kg
    }

    public static function brown1989_2($DBH, $H)
    {
        return exp(-3.1141 + 0.9719 * log(pow($DBH, 2) * $H));//unit: kg
    }

    public static function brown1989_3($DBH, $H, $WD)
    {
        return exp(-2.4090 + 0.9522 * log(pow($DBH, 2) * $H * $WD));//unit: kg
    }

    public static function brown1997_2($DBH)
    {
        return 21.297 - 6.953 * $DBH + 0.740 * pow($DBH, 2);//unit: kg
    }

    public static function brown1989_4($DBH, $H)
    {
        return exp(-3.3012 + 0.9439 * log(pow($DBH, 2) * $H));//unit: kg
    }

    public static function chave2005($D, $treeDensity = 0.6)
    {
        return $treeDensity * exp(-1.499 + 2.148 * log($D) + 0.207 * pow(log($D), 2) - 0.0281 * pow(log($D), 3));//unit: kg
    }

    /**
     * * Calculate aboveground biomass using Chave et al. (2014) Allometric Equation 1.
     *
     * Formula:
     * Biomass = 0.0673 * (Wood density * Tree height)^0.976
     *
     * @param float $pD Wood density in g/cm³.
     * @param float $H Tree height in meters.
     * @return float Aboveground biomass in kilograms (kg).
     *
     * Calculate aboveground biomass using Chave et al. (2014) Allometric Equation 2.
     *
     * Formula:
     * Biomass = exp(-1.803 - 0.976 * Environmental factor +
     *               0.976 * ln(Wood density) +
     *               2.673 * ln(Diameter) -
     *               0.0299 * (ln(Diameter))^2)
     *
     * @param float $E Environmental stress factor (dimensionless).
     * @param float $p Wood density in g/cm³.
     * @param float $D Diameter at breast height (DBH) in cm.
     * @return float Aboveground biomass in kilograms (kg).
     */
    public static function chave2014($p, $D, $H = null, $E = "")
    {
        if ($H !== null) {
            // Use equation with height
            return 0.0673 * pow(($p * pow($D, 2) * $H), 0.976);
        } else {
            // Use equation without height
            return exp(
                -1.803 - 0.976 * $E +
                0.976 * log($p) +
                2.673 * log($D) -
                0.0299 * pow(log($D), 2)
            );
        }
    }

    //calculate below ground biomass && carbon
    public static function mokany2006_BGC($AGC)
    {
        return ($AGC > 62.5) ? 0.235 * $AGC : 0.205 * $AGC;//unit: tC/ha
    }

    public static function mokany2006_BGB($AGB)
    {
        return 0.26 * $AGB;//unit: kg
    }

    public static function pearson2005($AGB)
    {
        return exp(-1.0587 + 0.8836 * log($AGB));//unit: kg
    }

    public static function luo2012($AGB)
    {
        return 0.221 * $AGB;
    }

    public static function toTones($data)
    {
        if ($data > 1000) {
            $converted = number_format($data / 1000, 2);
            $covertedWithUnit = $converted . ' t';
        } else {
            $converted = number_format($data, 2);
            $covertedWithUnit = $converted . ' kg';
        }

        return $covertedWithUnit;
    }

    /**
     * Calculate tree diversity based on species richness using the Shannon-Wiener index.
     *
     * @param int $totalTrees Total number of trees in the plot
     * @param int $speciesRichness Number of tree species in the plot
     * @return float Tree diversity index (Shannon-Wiener index)
     */
    public static function calculateShannonWienerIndex($totalTrees, $speciesRichness)
    {
        if ($totalTrees <= 0 || $speciesRichness <= 0) {
            return 0; // Return 0 if no trees or species present
        }

        // Calculate relative abundance of each species
        $relativeAbundance = $totalTrees / $speciesRichness;

        // Calculate Shannon-Wiener index
        $treeDiversity = 0;
        for ($i = 1; $i <= $speciesRichness; $i++) {
            $pi = $relativeAbundance / $totalTrees;
            $treeDiversity += $pi * log($pi);
        }
        $treeDiversity = -$treeDiversity;

        return $treeDiversity;
    }

    /**
     * Calculate tree diversity based on species richness using the Simpson index.
     *
     * @param int $totalTrees Total number of trees in the plot
     * @param int $speciesRichness Number of tree species in the plot
     * @return float Tree diversity index (Simpson index)
     */
    public static function calculateSimpsonIndex($totalTrees, $speciesRichness)
    {
        if ($totalTrees <= 0 || $speciesRichness <= 0) {
            return 0; // Return 0 if no trees or species present
        }

        // Calculate Simpson index
        $treeDiversity = 1;
        for ($i = 1; $i <= $speciesRichness; $i++) {
            $pi = $totalTrees / ($totalTrees - 1);
            $treeDiversity -= pow($pi, 2);
        }

        return $treeDiversity;
    }
}