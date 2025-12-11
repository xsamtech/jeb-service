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
        // Récupérer toutes les faces louées pour le mois et l'année donnés
        $rentedFaces = self::whereHas('face', function ($query) use ($month, $year) {
                                // Ici on filtre uniquement par la date de mise à jour de la location (updated_at)
                                $query->whereMonth('updated_at', $month)
                                    ->whereYear('updated_at', $year);
                            })
                            // Charger les dépenses associées à chaque `rentedFace`, filtrées par le mois et l'année de `outflow_date`
                            ->with(['expenses' => function ($query) use ($month, $year) {
                                // Appliquer le filtre de mois et année uniquement sur les dépenses (outflow_date)
                                $query->whereMonth('outflow_date', $month)
                                    ->whereYear('outflow_date', $year);
                            }])
                            ->get();

        $totalRemainingAmount = 0;

        foreach ($rentedFaces as $rentedFace) {
            // Somme des prix des faces louées
            $totalPrice = $rentedFace->price;

            // Somme des dépenses associées à cette face louée pour le mois et l'année donnés
            $totalExpenses = $rentedFace->expenses->sum('amount');

            // Calcul du montant restant
            $remainingAmount = $totalPrice - $totalExpenses;

            $totalRemainingAmount += $remainingAmount;
        }

        return $totalRemainingAmount;
    }
}
