<script>
// ============================================
// LEVELS 1-5: Questions Generator
// ============================================

const levels1To5 = {
    1: {
        name: "🌱 Level 1: Making 10",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🌱",
        hint: "Type the answer for: ",
        questions: []
    },
    2: {
        name: "🌿 Level 2: Addition Practice (0-9)",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🌿",
        hint: "Type the answer for: ",
        questions: []
    },
    3: {
        name: "🍃 Level 3: Making 10 & More",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🍃",
        hint: "Type the answer for: ",
        questions: []
    },
    4: {
        name: "⭐ Level 4: Adding Tens",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "⭐",
        hint: "10 + ? = ?",
        questions: []
    },
    5: {
        name: "🏆 Level 5: Adding Up to 20",
        targetScore: 100,
        questionsPerLevel: 10,
        icon: "🏆",
        hint: "Type the answer for: ",
        questions: []
    }
};

// Generate Level 1 questions
for (let i = 0; i < 10; i++) {
    const pairs = [[1,9],[2,8],[3,7],[4,6],[5,5],[6,4],[7,3],[8,2],[9,1],[0,10],[10,0]];
    const pair = pairs[Math.floor(Math.random() * pairs.length)];
    levels1To5[1].questions.push({
        text: pair[0] + ' + ' + pair[1],
        answer: 10,
        type: 'addition'
    });
}

// Generate Level 2 questions
for (let i = 0; i < 10; i++) {
    let num1 = Math.floor(Math.random() * 10);
    let maxNum2 = 9 - num1;
    let num2 = maxNum2 >= 0 ? Math.floor(Math.random() * (maxNum2 + 1)) : 0;
    let sum = num1 + num2;
    levels1To5[2].questions.push({
        text: num1 + ' + ' + num2,
        answer: sum,
        type: 'addition_level2'
    });
}

// Generate Level 3 questions (mix)
for (let i = 0; i < 5; i++) {
    const pairs = [[1,9],[2,8],[3,7],[4,6],[5,5],[6,4],[7,3],[8,2],[9,1],[0,10],[10,0]];
    const pair = pairs[Math.floor(Math.random() * pairs.length)];
    levels1To5[3].questions.push({
        text: pair[0] + ' + ' + pair[1],
        answer: 10,
        type: 'addition'
    });
}
for (let i = 0; i < 5; i++) {
    let num1 = Math.floor(Math.random() * 11);
    let maxNum2 = 10 - num1;
    let num2 = maxNum2 >= 0 ? Math.floor(Math.random() * (maxNum2 + 1)) : 0;
    let sum = num1 + num2;
    levels1To5[3].questions.push({
        text: num1 + ' + ' + num2,
        answer: sum,
        type: 'addition_level2'
    });
}
// Shuffle Level 3
for (let i = levels1To5[3].questions.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [levels1To5[3].questions[i], levels1To5[3].questions[j]] = [levels1To5[3].questions[j], levels1To5[3].questions[i]];
}

// Generate Level 4 questions
for (let i = 0; i < 10; i++) {
    let num = Math.floor(Math.random() * 9) + 1;
    levels1To5[4].questions.push({
        text: '10 + ' + num,
        answer: 10 + num,
        type: 'teen_addition'
    });
}

// Generate Level 5 questions
for (let i = 0; i < 10; i++) {
    let num1 = Math.floor(Math.random() * 21);
    let maxNum2 = 20 - num1;
    let num2 = maxNum2 >= 0 ? Math.floor(Math.random() * (maxNum2 + 1)) : 0;
    let sum = num1 + num2;
    levels1To5[5].questions.push({
        text: num1 + ' + ' + num2,
        answer: sum,
        type: 'addition_up_to_20'
    });
}
</script>