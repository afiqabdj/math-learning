<script>


const levels11To15 = {
    @php
        $level11Questions = [];
        $lastAnswer = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $givenNumber = mt_rand(1, 9); // 1-9
                $result = mt_rand(11, 20); // 11-20
                $answer = $result - $givenNumber;
                
                // Ensure answer is between 1-9
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
            
            $level11Questions[] = [
                'text' => '__ + ' . $givenNumber . ' = ' . $result,
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
        
        $level12Questions = [];
        $lastAnswer = null;
        
        for ($i = 0; $i < 10; $i++) {
            $attempts = 0;
            do {
                $givenNumber = mt_rand(10, 19); // 10-19
                $result = mt_rand(11, 20); // 11-20
                
                // Ensure result is greater than givenNumber
                if ($result <= $givenNumber) {
                    $result = $givenNumber + mt_rand(1, 10);
                    if ($result > 20) $result = 20;
                }
                
                $answer = $result - $givenNumber;
                
                // Ensure answer is 1-10
                if ($answer < 1 || $answer > 10) {
                    $answer = mt_rand(1, 10);
                    $givenNumber = $result - $answer;
                    if ($givenNumber < 10 || $givenNumber > 19) {
                        $givenNumber = mt_rand(10, 19);
                        $result = $givenNumber + $answer;
                    }
                }
                $attempts++;
                if ($attempts > 50) break;
            } while ($answer === $lastAnswer);
            
            $lastAnswer = $answer;
            
            $level12Questions[] = [
                'text' => '__ + ' . $givenNumber . ' = ' . $result,
                'answer' => $answer,
                'type' => 'missing'
            ];
        }
    
        $level13Questions = [];
        $lastText = null;
        
        // First, generate all possible questions from both styles
        $level11Pool = [];
        $level12Pool = [];
        
        // Generate Level 11 style questions
        for ($j = 0; $j < 15; $j++) {
            $givenNumber = mt_rand(1, 9);
            $result = mt_rand(11, 20);
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
            
            $level11Pool[] = [
                'text' => '__ + ' . $givenNumber . ' = ' . $result,
                'answer' => $answer
            ];
        }
        
        // Generate Level 12 style questions
        for ($j = 0; $j < 15; $j++) {
            $givenNumber = mt_rand(10, 19);
            $result = mt_rand(11, 20);
            
            if ($result <= $givenNumber) {
                $result = $givenNumber + mt_rand(1, 10);
                if ($result > 20) $result = 20;
            }
            
            $answer = $result - $givenNumber;
            
            if ($answer < 1 || $answer > 10) {
                $answer = mt_rand(1, 10);
                $givenNumber = $result - $answer;
                if ($givenNumber < 10 || $givenNumber > 19) {
                    $givenNumber = mt_rand(10, 19);
                    $result = $givenNumber + $answer;
                }
            }
            
            $level12Pool[] = [
                'text' => '__ + ' . $givenNumber . ' = ' . $result,
                'answer' => $answer
            ];
        }
        
        // Mix them together and pick 10 random ones
        $combinedPool = array_merge($level11Pool, $level12Pool);
        shuffle($combinedPool);
        
        for ($i = 0; $i < 10; $i++) {
            $q = $combinedPool[$i];
            $level13Questions[] = [
                'text' => $q['text'],
                'answer' => $q['answer'],
                'type' => 'missing'
            ];
        }
        
        $level14Questions = [];
        
        // Get questions from Level 9 style (10 + __ = result)
        $level9Pool = [];
        for ($j = 0; $j < 10; $j++) {
            $answer = mt_rand(1, 9);
            $result = 10 + $answer;
            $level9Pool[] = [
                'text' => '10 + __ = ' . $result,
                'answer' => $answer
            ];
        }
        
        // Get questions from Level 10 style (__ + x = result, x is 1-9)
        $level10Pool = [];
        for ($j = 0; $j < 10; $j++) {
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
            
            $level10Pool[] = [
                'text' => '__ + ' . $givenNumber . ' = ' . $result,
                'answer' => $answer
            ];
        }
        
        // Combine all pools
        $level14Pool = array_merge($level9Pool, $level10Pool, $level11Pool, $level12Pool);
        shuffle($level14Pool);
        
        for ($i = 0; $i < 10; $i++) {
            $q = $level14Pool[$i];
            $level14Questions[] = [
                'text' => $q['text'],
                'answer' => $q['answer'],
                'type' => 'missing'
            ];
        }
        
        $level15Questions = [];
        
        // Pool from Level 6 (__ + x = 10)
        $level6Pool = [];
        for ($j = 0; $j < 8; $j++) {
            $givenNumber = mt_rand(0, 10);
            $answer = 10 - $givenNumber;
            $level6Pool[] = [
                'text' => '__ + ' . $givenNumber . ' = 10',
                'answer' => $answer
            ];
        }
        
        // Pool from Level 7 (x + __ = 10)
        $level7Pool = [];
        for ($j = 0; $j < 8; $j++) {
            $givenNumber = mt_rand(0, 10);
            $answer = 10 - $givenNumber;
            $level7Pool[] = [
                'text' => $givenNumber . ' + __ = 10',
                'answer' => $answer
            ];
        }
        
        // Pool from Level 8 (mixed first/second)
        $level8Pool = [];
        for ($j = 0; $j < 8; $j++) {
            $position = mt_rand(0, 1);
            $givenNumber = mt_rand(0, 10);
            $answer = 10 - $givenNumber;
            
            if ($position === 0) {
                $text = '__ + ' . $givenNumber . ' = 10';
            } else {
                $text = $givenNumber . ' + __ = 10';
            }
            
            $level8Pool[] = [
                'text' => $text,
                'answer' => $answer
            ];
        }
        
        // Combine all pools
        $level15Pool = array_merge($level6Pool, $level7Pool, $level8Pool, $level9Pool, $level10Pool);
        shuffle($level15Pool);
        
        for ($i = 0; $i < 10; $i++) {
            $q = $level15Pool[$i];
            $level15Questions[] = [
                'text' => $q['text'],
                'answer' => $q['answer'],
                'type' => 'missing'
            ];
        }
        
    @endphp
    
    11: {
        name: "🔷 Level 11: Missing Number - Teen Sums (1-9)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔷",
        hint: "Type the missing number (1-9): ",
        questions: @json($level11Questions)
    },
    12: {
        name: "🔶 Level 12: Missing Number - Teen Sums (10-19)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔶",
        hint: "Type the missing number: ",
        questions: @json($level12Questions)
    },
    13: {
        name: "🟢 Level 13: Missing Number - Mixed Teen Sums",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟢",
        hint: "Type the missing number: ",
        questions: @json($level13Questions)
    },
    14: {
        name: "🟡 Level 14: Ultimate Review - Teen Numbers",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟡",
        hint: "Type the missing number: ",
        questions: @json($level14Questions)
    },
    15: {
        name: "🔴 Level 15: Mega Review - All Missing Numbers",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔴",
        hint: "Type the missing number: ",
        questions: @json($level15Questions)
    }
};
</script>