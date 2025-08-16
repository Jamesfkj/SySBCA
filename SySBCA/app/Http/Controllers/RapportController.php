<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\ReferenceRapport;

class RapportController extends Controller
{
    public function verifierRapport(Request $request)
    {
        $token = $request->query('token');
        
        try {
            $data = json_decode(Crypt::decryptString($token), true);
            $uuid = $data['uuid'];
            
            $reference = ReferenceRapport::where('uuid', $uuid)->first();
            if (!$reference) {
                $verification = [
                    'status' => 'erreur', 
                    'message' => 'Rapport non valide'
                ];
            } else {
                $verification = [
                    'status' => 'authentique',
                    'uuid' => $uuid,
                    'Créateur' => $reference->utilisateur->email,
                    
                    'date de creation' => $reference->created_at->format('d/m/Y H:i'),
                ];
            }
        } catch (\Exception $e) {
            $verification = [
                'status' => 'erreur', 
                'message' => 'Token invalide'
            ];
        }

        // Retourner la vue au lieu du JSON
        return view('verificationRapport', compact('verification'));
    }
}