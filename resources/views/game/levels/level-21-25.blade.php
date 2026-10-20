<script>
// ============================================
// LEVELS 21-25: Two-Digit Addition & Missing Numbers
// Level 21: Addition (10-50)
// Level 22: Missing first number (result 10-35)
// Level 23: Missing first number (result 10-50)
// Level 24: Missing second number (result 10-35)
// Level 25: Missing second number (result 10-50)
// ============================================

const levels21To25 = {
    // ============================================
    // LEVEL 21: Addition (10-50)
    // Both numbers are between 0-50, sum is constrained
    // ============================================
    21: {
        name: "🔷 Level 21: Addition (10-50)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔷",
        hint: "Type the sum (10-50): ",
        questions: []
    },
    
    // ============================================
    // LEVEL 22: Missing first number (__ + x = result)
    // Result between 10-35, x between 1-20
    // Answer = result - x
    // ============================================
    22: {
        name: "🔶 Level 22: Find the Missing Number (Result 10-35)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔶",
        hint: "Type the missing number: ",
        questions: []
    },
    
    // ============================================
    // LEVEL 23: Missing first number (__ + x = result)
    // Result between 10-50, x between 1-25
    // Answer = result - x
    // ============================================
    23: {
        name: "🟢 Level 23: Find the Missing Number (Result 10-50)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟢",
        hint: "Type the missing number: ",
        questions: []
    },
    
    // ============================================
    // LEVEL 24: Missing second number (x + __ = result)
    // Result between 10-35, x between 1-20
    // Answer = result - x
    // ============================================
    24: {
        name: "🟡 Level 24: Find the Missing Number (Result 10-35)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🟡",
        hint: "Type the missing number: ",
        questions: []
    },
    
    // ============================================
    // LEVEL 25: Missing second number (x + __ = result)
    // Result between 10-50, x between 1-25
    // Answer = result - x
    // ============================================
    25: {
        name: "🔴 Level 25: Find the Missing Number (Result 10-50)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🔴",
        hint: "Type the missing number: ",
        questions: []
    }
};

// ============================================
// GENERATE LEVEL 21 QUESTIONS (Addition 10-50)
// ============================================
for (let i = 0; i < 10; i++) {
    const minSum = 10;
    const maxSum = 50;
    const sum = Math.floor(Math.random() * (maxSum - minSum + 1)) + minSum;
    
    // Generate two numbers that add up to sum
    let num1 = Math.floor(Math.random() * (sum + 1));
    let num2 = sum - num1;
    
    // Ensure both numbers are positive and reasonable
    if (num1 < 0) num1 = 0;
    if (num2 < 0) num2 = 0;
    
    levels21To25[21].questions.push({
        text: `${num1} + ${num2}`,
        answer: sum,
        type: 'addition_21'
    });
}

// ============================================
// GENERATE LEVEL 22 QUESTIONS (Missing first, result 10-35)
// __ + x = result, answer = result - x
// ============================================
for (let i = 0; i < 10; i++) {
    // Result between 10-35
    const result = Math.floor(Math.random() * 26) + 10; // 10-35
    
    // x between 1-20, must be less than result
    const maxX = Math.min(20, result - 1);
    const minX = 1;
    const x = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
    
    const answer = result - x;
    
    levels21To25[22].questions.push({
        text: `⬜ + ${x} = ${result}`,
        answer: answer,
        type: 'missing_first_22'
    });
}

// ============================================
// GENERATE LEVEL 23 QUESTIONS (Missing first, result 10-50)
// __ + x = result, answer = result - x
// ============================================
for (let i = 0; i < 10; i++) {
    // Result between 10-50
    const result = Math.floor(Math.random() * 41) + 10; // 10-50
    
    // x between 1-25, must be less than result
    const maxX = Math.min(25, result - 1);
    const minX = 1;
    const x = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
    
    const answer = result - x;
    
    levels21To25[23].questions.push({
        text: `⬜ + ${x} = ${result}`,
        answer: answer,
        type: 'missing_first_23'
    });
}

// ============================================
// GENERATE LEVEL 24 QUESTIONS (Missing second, result 10-35)
// x + __ = result, answer = result - x
// ============================================
for (let i = 0; i < 10; i++) {
    // Result between 10-35
    const result = Math.floor(Math.random() * 26) + 10; // 10-35
    
    // x between 1-20, must be less than result
    const maxX = Math.min(20, result - 1);
    const minX = 1;
    const x = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
    
    const answer = result - x;
    
    levels21To25[24].questions.push({
        text: `${x} + ⬜ = ${result}`,
        answer: answer,
        type: 'missing_second_24'
    });
}

// ============================================
// GENERATE LEVEL 25 QUESTIONS (Missing second, result 10-50)
// x + __ = result, answer = result - x
// ============================================
for (let i = 0; i < 10; i++) {
    // Result between 10-50
    const result = Math.floor(Math.random() * 41) + 10; // 10-50
    
    // x between 1-25, must be less than result
    const maxX = Math.min(25, result - 1);
    const minX = 1;
    const x = Math.floor(Math.random() * (maxX - minX + 1)) + minX;
    
    const answer = result - x;
    
    levels21To25[25].questions.push({
        text: `${x} + ⬜ = ${result}`,
        answer: answer,
        type: 'missing_second_25'
    });
}

// Log for debugging
console.log('Levels 21-25 generated:');
console.log(`Level 21: ${levels21To25[21].questions.length} questions`);
console.log(`Level 22: ${levels21To25[22].questions.length} questions`);
console.log(`Level 23: ${levels21To25[23].questions.length} questions`);
console.log(`Level 24: ${levels21To25[24].questions.length} questions`);
console.log(`Level 25: ${levels21To25[25].questions.length} questions`);
</script>