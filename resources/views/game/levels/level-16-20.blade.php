<script>
// ============================================
// LEVELS 16-20: THREE NUMBERS ADDITION
// ============================================
// CYCLE 1: Drag & Drop - Make 10 in Box 1 strategy
// CYCLE 2: Simple Practice - Three numbers where two make 10 (direct input)
// ============================================

const levels16To20 = {
    16: {
        name: "🔷 Level 16: Three Numbers - Make 10 First!",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔷",
        hint: "Find two numbers that add to 10, then add the third!",
        type: "drag_drop"
    },
    17: {
        name: "🔶 Level 17: Three Numbers - Make 10 First!",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔶",
        hint: "Find the pair that adds up to 10!",
        type: "drag_drop"
    },
    18: {
        name: "🟢 Level 18: Three Numbers - Make 10 First!",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟢",
        hint: "Make 10 first, then add the remaining number!",
        type: "drag_drop"
    },
    19: {
        name: "🟡 Level 19: Three Numbers - Make 10 First!",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟡",
        hint: "Quick! Find the pair that makes 10!",
        type: "drag_drop"
    },
    20: {
        name: "🔴 Level 20: Three Numbers - Make 10 First!",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔴",
        hint: "Master the make-10 strategy!",
        type: "drag_drop"
    }
};

// ============================================
// CYCLE 1: DRAG & DROP QUESTIONS (Original)
// ============================================
function generateThreeNumberDragDropQuestions(levelNum) {
    const questions = [];
    const pairs = [[1,9], [2,8], [3,7], [4,6], [5,5], [6,4], [7,3], [8,2], [9,1]];
    
    let maxThirdNumber = 9;
    if (levelNum >= 17) maxThirdNumber = 12;
    if (levelNum >= 18) maxThirdNumber = 15;
    if (levelNum >= 19) maxThirdNumber = 18;
    if (levelNum >= 20) maxThirdNumber = 20;
    
    for (let i = 0; i < 10; i++) {
        const pair = pairs[Math.floor(Math.random() * pairs.length)];
        
        let thirdNumber;
        do {
            thirdNumber = Math.floor(Math.random() * (maxThirdNumber - 1)) + 2;
        } while (thirdNumber === pair[0] || thirdNumber === pair[1]);
        
        const numbers = [pair[0], pair[1], thirdNumber];
        for (let j = numbers.length - 1; j > 0; j--) {
            const k = Math.floor(Math.random() * (j + 1));
            [numbers[j], numbers[k]] = [numbers[k], numbers[j]];
        }
        
        questions.push({
            numbers: numbers,
            pair: pair,
            remaining: thirdNumber,
            total: 10 + thirdNumber,
            text: numbers.join(' + '),
            answer: 10 + thirdNumber
        });
    }
    
    return questions;
}

// ============================================
// CYCLE 2: SIMPLE PRACTICE QUESTIONS
// Three numbers where TWO numbers add up to 10
// Example: 1 + 8 + 9 = ? (1+9=10, then 10+8=18)
// Example: 5 + 5 + 2 = ? (5+5=10, then 10+2=12)
// Example: 2 + 1 + 8 = ? (2+8=10, then 10+1=11)
// ============================================
function generateThreeNumberPracticeQuestions(levelNum) {
    const questions = [];
    
    // All possible pairs that make 10
    const pairsThatMake10 = [
        [1, 9], [2, 8], [3, 7], [4, 6], 
        [5, 5], [6, 4], [7, 3], [8, 2], [9, 1], [0, 10], [10, 0]
    ];
    
    // Third number ranges based on level difficulty
    let minThirdNumber = 1;
    let maxThirdNumber = 9;
    
    if (levelNum >= 17) {
        maxThirdNumber = 12;
    }
    if (levelNum >= 18) {
        maxThirdNumber = 15;
    }
    if (levelNum >= 19) {
        maxThirdNumber = 18;
    }
    if (levelNum >= 20) {
        maxThirdNumber = 20;
    }
    
    // Also allow negative numbers? No, keep positive for this level
    // But we can allow 0 as a third number for variety
    if (levelNum >= 18) {
        minThirdNumber = 0;
    }
    
    // Generate 25 unique questions for Cycle 2 (so there's variety)
    for (let i = 0; i < 25; i++) {
        // Pick a random pair that makes 10
        const pair = pairsThatMake10[Math.floor(Math.random() * pairsThatMake10.length)];
        
        // Pick a third number (can be same as pair numbers, that's fine for practice)
        let thirdNumber;
        do {
            thirdNumber = Math.floor(Math.random() * (maxThirdNumber - minThirdNumber + 1)) + minThirdNumber;
        } while (false); // No restriction - third number can be anything
        
        // Create the three numbers array (will shuffle them)
        const numbers = [pair[0], pair[1], thirdNumber];
        
        // Shuffle the numbers so they appear in random order
        for (let j = numbers.length - 1; j > 0; j--) {
            const k = Math.floor(Math.random() * (j + 1));
            [numbers[j], numbers[k]] = [numbers[k], numbers[j]];
        }
        
        // Calculate total: (pair[0] + pair[1]) + thirdNumber = 10 + thirdNumber
        const total = 10 + thirdNumber;
        
        questions.push({
            text: numbers.join(' + '),
            answer: total,
            pair: pair,
            thirdNumber: thirdNumber,
            // For debugging/display purposes
            explanation: `(${pair[0]} + ${pair[1]} = 10, then 10 + ${thirdNumber} = ${total})`
        });
    }
    
    return questions;
}

// ============================================
// GENERATE QUESTIONS FOR BOTH CYCLES
// ============================================

// Cycle 1: Drag & Drop Questions (original)
levels16To20[16].questions = generateThreeNumberDragDropQuestions(16);
levels16To20[17].questions = generateThreeNumberDragDropQuestions(17);
levels16To20[18].questions = generateThreeNumberDragDropQuestions(18);
levels16To20[19].questions = generateThreeNumberDragDropQuestions(19);
levels16To20[20].questions = generateThreeNumberDragDropQuestions(20);

// Cycle 2: Simple Practice Questions (where two numbers make 10)
// These will be used for the practice mode (like Cycle 1 in levels 1-15)
levels16To20[16].cycle2Questions = generateThreeNumberPracticeQuestions(16);
levels16To20[17].cycle2Questions = generateThreeNumberPracticeQuestions(17);
levels16To20[18].cycle2Questions = generateThreeNumberPracticeQuestions(18);
levels16To20[19].cycle2Questions = generateThreeNumberPracticeQuestions(19);
levels16To20[20].cycle2Questions = generateThreeNumberPracticeQuestions(20);

// For debugging - log sample questions
console.log("=== CYCLE 2 PRACTICE QUESTIONS (Levels 16-20) ===");
console.log("Level 16 sample (1 + 8 + 9 style):", levels16To20[16].cycle2Questions.slice(0, 3));
console.log("Level 20 sample:", levels16To20[20].cycle2Questions.slice(0, 3));
</script>