<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages de validation en français
    |--------------------------------------------------------------------------
    |
    | Ce fichier remplace/complète le fichier par défaut fourni par Laravel.
    | S'il n'existait pas dans votre projet, c'est pour ça que Laravel
    | affichait la clé brute (ex: "validation.unique") au lieu du message.
    |
    */

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'active_url'           => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure à :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale à :date.',
    'alpha'                => 'Le champ :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne peut contenir que des lettres, des chiffres, des tirets et des tirets bas.',
    'alpha_num'            => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'before'               => 'Le champ :attribute doit être une date antérieure à :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale à :date.',
    'between'              => [
        'numeric' => 'La valeur du champ :attribute doit être comprise entre :min et :max.',
        'file'    => 'La taille du champ :attribute doit être comprise entre :min et :max kilo-octets.',
        'string'  => 'Le texte du champ :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le tableau du champ :attribute doit contenir entre :min et :max éléments.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'Le champ de confirmation :attribute ne correspond pas.',
    'date'                 => 'Le champ :attribute n\'est pas une date valide.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit contenir :digits chiffres.',
    'email'                => 'Le champ :attribute doit être une adresse email valide.',
    'exists'               => 'Le champ :attribute sélectionné est invalide.',
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ :attribute sélectionné est invalide.',
    'integer'              => 'Le champ :attribute doit être un entier.',
    'max'                  => [
        'numeric' => 'La valeur du champ :attribute ne peut pas être supérieure à :max.',
        'file'    => 'Le champ :attribute ne peut pas être supérieur à :max kilo-octets.',
        'string'  => 'Le texte du champ :attribute ne peut pas contenir plus de :max caractères.',
        'array'   => 'Le tableau du champ :attribute ne peut pas contenir plus de :max éléments.',
    ],
    'min'                  => [
        'numeric' => 'La valeur du champ :attribute doit être au moins de :min.',
        'file'    => 'Le champ :attribute doit être au moins de :min kilo-octets.',
        'string'  => 'Le texte du champ :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le tableau du champ :attribute doit contenir au moins :min éléments.',
    ],
    'not_in'               => 'Le champ :attribute sélectionné est invalide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'regex'                => 'Le format du champ :attribute est invalide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_if'          => 'Le champ :attribute est obligatoire quand :other vaut :value.',
    'same'                 => 'Les champs :attribute et :other doivent être identiques.',
    'size'                 => [
        'numeric' => 'La taille du champ :attribute doit être de :size.',
        'file'    => 'Le champ :attribute doit être de :size kilo-octets.',
        'string'  => 'Le texte du champ :attribute doit contenir :size caractères.',
        'array'   => 'Le tableau du champ :attribute doit contenir :size éléments.',
    ],
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique'               => 'La valeur du champ :attribute est déjà utilisée.',
    'url'                  => 'Le format de l\'URL du champ :attribute est invalide.',

    /*
    |--------------------------------------------------------------------------
    | Messages personnalisés (spécifiques à un champ + une règle)
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'numero' => [
            'unique'   => 'Ce numéro de chambre existe déjà, veuillez en choisir un autre.',
            'required' => 'Veuillez saisir un numéro de chambre.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Noms des attributs en français
    |--------------------------------------------------------------------------
    | Utilisés pour remplacer :attribute dans les messages ci-dessus.
    */

    'attributes' => [
        'numero'   => 'numéro de chambre',
        'type'     => 'type de chambre',
        'bloc'     => 'bloc',
        'etage'    => 'étage',
        'capacite' => 'capacité',
    ],

];