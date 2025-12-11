<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class RentedFace extends Model
{
    use HasFactory;

    protected $table = 'rented_faces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One user for several accountancies
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ONE-TO-MANY
     * One face for several accountancies
     */
    public function face(): BelongsTo
    {
        return $this->belongsTo(Face::class);
    }

    /**
     * MANY-TO-ONE
     * Several expenses for a rented_face
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Calculer la somme des montants restants pour un mois et une année donnés
     *
     * @param int $month
     * @param int $year
     * @return float
     */
    public static function getRemainingAmountByMonthYear($month, $year)
    {
        // Obtenez toutes les faces louées pour le mois et l'année donnés
        $rentedFaces = self::whereHas('face', function ($query) use ($month, $year) {
                                $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
                            })->with('expenses')->get();

        $totalRemainingAmount = 0;

        foreach ($rentedFaces as $rentedFace) {
            // Somme des prix des faces louées
            $totalPrice = $rentedFace->price;

            // Somme des dépenses associées à cette face louée pour le mois et l'année donnés
            $totalExpenses = $rentedFace->expenses->whereMonth('outflow_date', $month)->whereYear('outflow_date', $year)->sum('amount');

            // Calcul du montant restant
            $remainingAmount = $totalPrice - $totalExpenses;

            $totalRemainingAmount += $remainingAmount;
        }

        return $totalRemainingAmount;
    }
}
