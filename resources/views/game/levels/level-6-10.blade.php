<script>
// ============================================
// LEVELS 6-10: MISSING NUMBER WITH KEYBOARD INPUT
// ============================================

const levels6To10 = {
    @php
        // ============================================
        // LEVEL 6: Missing number at the beginning (__ + x = 10)
        // ============================================
        $level6Questions = [];
        $lastAnswer = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $givenNumber = mt_rand(0, 10);
                $answer = 10 - $givenNumber;
                $attempts++;
                if ($attempts > 30) break;
            } while ($answer === $lastAnswer);
            
            $lastAnswer = $answer;
            
            $level6Questions[] = [
                'text' => '⬜ + ' . $givenNumber . ' = 10',  // Full equation already
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
        
        // ============================================
        // LEVEL 7: Missing number at the end (x + __ = 10)
        // ============================================
        $level7Questions = [];
        $lastAnswer = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $givenNumber = mt_rand(0, 10);
                $answer = 10 - $givenNumber;
                $attempts++;
                if ($attempts > 30) break;
            } while ($answer === $lastAnswer);
            
            $lastAnswer = $answer;
            
            $level7Questions[] = [
                'text' => $givenNumber . ' + ⬜= 10',  // Full equation already
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
        
        // ============================================
        // LEVEL 8: Mix of Level 6 & 7
        // ============================================
        $level8Questions = [];
        $lastText = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $position = mt_rand(0, 1);
                $givenNumber = mt_rand(0, 10);
                $answer = 10 - $givenNumber;
                
                if ($position === 0) {
                    $text = '⬜ + ' . $givenNumber . ' = 10';
                } else {
                    $text = $givenNumber . ' + ⬜ = 10';
                }
                $attempts++;
                if ($attempts > 30) break;
            } while ($text === $lastText);
            
            $lastText = $text;
            
            $level8Questions[] = [
                'text' => $text,  // Full equation already
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
        
        // ============================================
        // LEVEL 9: 10 + __ = (11-19)
        // ============================================
        $level9Questions = [];
        $lastAnswer = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $answer = mt_rand(1, 9);
                $result = 10 + $answer;
                $attempts++;
                if ($attempts > 30) break;
            } while ($answer === $lastAnswer);
            
            $lastAnswer = $answer;
            
            $level9Questions[] = [
                'text' => '10 + ⬜ = ' . $result,  // Full equation already
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
        
        // ============================================
        // LEVEL 10: __ + x = (11-19)
        // ============================================
        $level10Questions = [];
        $lastAnswer = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $givenNumber = mt_rand(1, 9);
                $result = mt_rand(11, 19);
                $answer = $result - $givenNumber;
                
                if ($answer < 1 || $answer > 9) {
                    $answer = mt_rand(1, 9);
                    $givenNumber = $result - $answer;
                    if ($givenNumber < 1 || $givenNumber > 9) {
                        $answer = mt_rand(1, 9);
                        $givenNumber = mt_rand(1, 9);
                        $result = $givenNumber + $answer;
                    }
                }
                $attempts++;
                if ($attempts > 50) break;
            } while ($answer === $lastAnswer);
            
            $lastAnswer = $answer;
            
            $level10Questions[] = [
                'text' => '⬜ + ' . $givenNumber . ' = ' . $result,  // Full equation already
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
    @endphp
    
    6: {
        name: "🔷 Level 6: Find the Missing Number (Beginning)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔷",
        hint: "Type the missing number: ",
        questions: @json($level6Questions)
    },
    7: {
        name: "🔶 Level 7: Find the Missing Number (End)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔶",
        hint: "Type the missing number: ",
        questions: @json($level7Questions)
    },
    8: {
        name: "🟢 Level 8: Missing Number - Mixed",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟢",
        hint: "Type the missing number: ",
        questions: @json($level8Questions)
    },
    9: {
        name: "🟡 Level 9: Teen Numbers - Missing End",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟡",
        hint: "Type the missing number: ",
        questions: @json($level9Questions)
    },
    10: {
        name: "🔴 Level 10: Teen Numbers - Missing Beginning",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔴",
        hint: "Type the missing number (1-9): ",
        questions: @json($level10Questions)
    }
};
</script>